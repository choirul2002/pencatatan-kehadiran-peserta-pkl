package com.example.android.treasureHunt

import android.animation.Animator
import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.app.Activity
import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.graphics.Color
import android.graphics.drawable.ColorDrawable
import android.os.AsyncTask
import android.os.Bundle
import android.os.Handler
import android.speech.RecognizerIntent
import android.view.Menu
import android.view.MenuItem
import android.view.View
import android.widget.ArrayAdapter
import android.widget.Spinner
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.SearchView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.LinearLayoutManager
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.github.barteksc.pdfviewer.PDFView
import com.loopj.android.http.AsyncHttpClient
import com.loopj.android.http.AsyncHttpResponseHandler
import com.loopj.android.http.RequestParams
import cz.msebera.android.httpclient.Header
import kotlinx.android.synthetic.main.activity_presensi_peserta.*
import kotlinx.android.synthetic.main.activity_presensi_peserta.imageView19
import kotlinx.android.synthetic.main.activity_presensi_peserta.lsForum
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import mumayank.com.airlocationlibrary.AirLocation
import org.json.JSONArray
import org.json.JSONObject
import java.io.File
import java.io.FileOutputStream
import java.net.URL

class PresensiActivity: AppCompatActivity() {
    val daftarForum = mutableListOf<HashMap<String,String>>()
    val daftarTahun = mutableListOf<HashMap<String,String>>()
    val presensi = "http://192.168.43.57/simaptapkl/public/service/absensi.php"
    lateinit var forumPresensi :AdapterPresensi
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    var lihat = "0"
    lateinit var searchView: SearchView
    lateinit var isDialog: AlertDialog
    lateinit var absensiItem: MenuItem
    lateinit var searchItem: MenuItem
    lateinit var micItem: MenuItem
    var SPEECH_REQUEST_CODE = 30
    lateinit var alertDialog: AlertDialog
    var cekKoneksi = "1"
    var airLoc : AirLocation? = null

    private lateinit var spinner: Spinner
    private lateinit var spinner2: Spinner

    private val items = arrayOf(
        Item(". . ."),
        Item("Januari"),
        Item("Februari"),
        Item("Maret"),
        Item("April"),
        Item("Mei"),
        Item("Juni"),
        Item("Juli"),
        Item("Agustus"),
        Item("September"),
        Item("Oktober"),
        Item("November"),
        Item("Desember")
    )

    private val items2 = arrayOf(
        Item(". . .")
    )

    data class Item(val name: String)
    private var selectedItem: Item? = null
    private var selectedItem2: Item? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_presensi_peserta)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.setTitle("Absensi")
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)

        forumPresensi = AdapterPresensi(daftarForum, this)
        lsForum.layoutManager = LinearLayoutManager(this)
        lsForum.adapter = forumPresensi

        refreshUp()

        var paket : Bundle? = intent.extras
        var notifikasi = paket?.getString("notifikasi").toString()

        if(notifikasi.equals("1")){
            dataPresensi(paket?.getString("kode").toString())
            jumlahDataPresensi(paket?.getString("kode").toString())

            dataTahun()
        }else{
            val item1 = HashMap<String, String>()
            item1["tahun"] = ". . ."
            daftarTahun.add(item1)
            dataPresensi()
            jumlahDataPresensi()
            dataTahun()
        }

        val networkConnection = NetworkConnection(applicationContext)
        networkConnection.observe(this, { isConnected ->
            if (isConnected) {
                if(cekKoneksi.equals("0")){
                    isDismissInternet()
                    cekKoneksi = "1"
                }else{
                    cekKoneksi = "1"
                }
            } else {
                cekKoneksi = "0"
                startLoadingInternet()
            }
        })
    }

    val cetak = "http://192.168.43.57/simaptapkl/public/service/cetak.php"

    fun dataTahun(){
        val request = object : StringRequest(
            Method.POST,cetak,
            Response.Listener { response ->
                daftarTahun.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("tahun",jsonObject.getString("TAHUN_TIM"))

                    daftarTahun.add(frm)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","tahun")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun startLoadingInternet(){
        val inflater = this.layoutInflater
        val dialogView = inflater.inflate(R.layout.circle_loading_internet,null)

        val builder = AlertDialog.Builder(this)
        builder.setView(dialogView)
        builder.setCancelable(false)
        isDialog = builder.create()
        isDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
        isDialog.show()
    }

    fun isDismissInternet(){
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                isDialog.dismiss()
            }
        },2000)
    }

    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.menu_aksi_presensi, menu)
        searchItem = menu?.findItem(R.id.searchItem)!!
        micItem = menu?.findItem(R.id.micItem)!!
        absensiItem = menu?.findItem(R.id.itemAbsensi)!!
        searchView = searchItem?.actionView as SearchView

        searchView.queryHint = "Search Here ..."
        searchView.setOnQueryTextListener(object : SearchView.OnQueryTextListener {
            override fun onQueryTextSubmit(query: String?): Boolean {
                return false
            }

            override fun onQueryTextChange(newText: String?): Boolean {
                forumPresensi.filter.filter(newText)
                return true
            }
        })

        return super.onCreateOptionsMenu(menu)
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)
        if (resultCode == Activity.RESULT_OK) {
            if(requestCode == SPEECH_REQUEST_CODE){
                val spokenText: String? =
                    data?.getStringArrayListExtra(RecognizerIntent.EXTRA_RESULTS).let { results ->
                        results?.get(0)
                    }
                // Do something with spokenText
                searchView.setQuery(spokenText, false)
            }
        }

        if(requestCode == 10){
            dataPresensi()
            jumlahDataPresensi()
        }

        super.onActivityResult(requestCode, resultCode, data)
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when(item?.itemId){
            R.id.micItem->{
                val intent = Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH).apply {
                    putExtra(
                        RecognizerIntent.EXTRA_LANGUAGE_MODEL,
                        RecognizerIntent.LANGUAGE_MODEL_FREE_FORM
                    )
                }
                startActivityForResult(intent, SPEECH_REQUEST_CODE)
            }
            R.id.itemAbsensi->{
                val inflater = layoutInflater
                val dialogView = inflater.inflate(R.layout.activity_form_rekap,null)
                val kembali = dialogView.findViewById<TextView>(R.id.textView129)
                val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                spinner = dialogView.findViewById<Spinner>(R.id.spinBulan)
                spinner2 = dialogView.findViewById<Spinner>(R.id.spinTahun)

                val spinnerData = items.map { it.name }
                val spinnerData2 = daftarTahun.map { it["tahun"] }

                val adapter = ArrayAdapter(this, android.R.layout.simple_spinner_item, spinnerData)
                val adapter2 = ArrayAdapter(this, android.R.layout.simple_spinner_item, spinnerData2)
                adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
                adapter2.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
                spinner.adapter = adapter
                spinner2.adapter = adapter2

                kembali.setText("Kembali")
                kirim.setText("Filter")

                kembali.setOnClickListener{
                    alertDialog.dismiss()
                }

                kirim.setOnClickListener{
                    val selectedItem = spinner.selectedItem as? String
                    val selectedItem2 = spinner2.selectedItem as? String

                    if(selectedItem.equals(". . . ") || selectedItem2.equals(". . .")){
                        Toast.makeText(this, "Pilih data dengan benar", Toast.LENGTH_SHORT).show()
                    }else{
                        alertDialog.dismiss()
                        lihat = "2"

                        dataRekap(this, selectedItem.toString(), selectedItem2.toString())
                    }
                }

                val builder = android.app.AlertDialog.Builder(this@PresensiActivity)
                builder.setView(dialogView)
                alertDialog = builder.create()
                alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                alertDialog.show()
            }
        }
        return super.onOptionsItemSelected(item)
    }

    fun cetakPDF(context:Context, bulan: String, tahun: String){
        val client = AsyncHttpClient()
        val params = RequestParams()
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        params.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
        params.put("pilihan", "rekapSeluruhKehadiranPembimbing")
        params.put("bulan", bulan)
        params.put("tahun", tahun)

        client.post(
            "http://192.168.43.57/simaptapkl/public/service/cetak.php",
            params,
            object : AsyncHttpResponseHandler() {
                override fun onSuccess(
                    statusCode: Int,
                    headers: Array<Header?>?,
                    responseBody: ByteArray?
                ) {
                    viewPDF(context)
                }

                override fun onFailure(
                    statusCode: Int,
                    headers: Array<Header?>?,
                    responseBody: ByteArray?,
                    error: Throwable?
                ) {
                    viewPDF2(context)
                }
            })
    }

    fun viewPDF(context:Context){
        val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            absensiItem.isVisible = false

                            val pdfView = findViewById<PDFView>(R.id.pdfViewPembimbing)
                            val urlLihat = "http://192.168.43.57/simaptapkl/public/service/generate/Daftar-rekap-kehadiran.pdf"

                            val downloadTask = object : AsyncTask<Void, Void, File>() {
                                override fun doInBackground(vararg params: Void?): File {
                                    val inputStream = URL(urlLihat).openStream()
                                    val file = File(context.cacheDir, "file.pdf")
                                    FileOutputStream(file).use { outputStream ->
                                        val buffer = ByteArray(4 * 1024)
                                        var bytesRead = inputStream.read(buffer)
                                        while (bytesRead != -1) {
                                            outputStream.write(buffer, 0, bytesRead)
                                            bytesRead = inputStream.read(buffer)
                                        }
                                        outputStream.flush()
                                    }
                                    return file
                                }

                                override fun onPostExecute(result: File) {
                                    pdfView.fromFile(result).load()
                                }
                            }

                            downloadTask.execute()

                            supportActionBar?.setTitle("Rekap Kehadiran")
                            supportActionBar?.apply {
                                setBackgroundDrawable(ColorDrawable(Color.BLACK))
                            }

                            searchItem.isVisible = false
                            micItem.isVisible = false

                            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                                duration = 250
                                addUpdateListener {
                                    val value = it.animatedValue as Float
                                    pdfView.scaleX = value
                                    pdfView.scaleY = value
                                }
                            }

                            val visibilityAnimator = ObjectAnimator.ofFloat(flAbsensiViewPembimbing, "alpha", 0f, 1f).apply {
                                duration = 250
                            }

                            val animatorSet = AnimatorSet().apply {
                                playTogether(animator, visibilityAnimator)
                            }

                            flAbsensiViewPembimbing.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
                                override fun onLayoutChange(
                                    view: View?,
                                    left: Int,
                                    top: Int,
                                    right: Int,
                                    bottom: Int,
                                    oldLeft: Int,
                                    oldTop: Int,
                                    oldRight: Int,
                                    oldBottom: Int
                                ) {
                                    if (view?.visibility == View.GONE) {
                                        pdfView.scaleX = 0f
                                        pdfView.scaleY = 0f
                                    }
                                }
                            })

                            window.statusBarColor = ContextCompat.getColor(context, R.color.black)

                            flAbsensiViewPembimbing.visibility = View.VISIBLE
                            animatorSet.start()
                        }
                    },3000)
    }

    fun viewPDF2(context:Context){
        val loading = LoadingDialog(this)
        loading.startLoading()
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                loading.isDismiss()
                absensiItem.isVisible = false

                val pdfView = findViewById<PDFView>(R.id.pdfViewPembimbing)
                val urlLihat = "http://192.168.43.57/simaptapkl/public/service/generate/Daftar-rekap-kehadiran.pdf"

                val downloadTask = object : AsyncTask<Void, Void, File>() {
                    override fun doInBackground(vararg params: Void?): File {
                        val inputStream = URL(urlLihat).openStream()
                        val file = File(context.cacheDir, "file.pdf")
                        FileOutputStream(file).use { outputStream ->
                            val buffer = ByteArray(4 * 1024)
                            var bytesRead = inputStream.read(buffer)
                            while (bytesRead != -1) {
                                outputStream.write(buffer, 0, bytesRead)
                                bytesRead = inputStream.read(buffer)
                            }
                            outputStream.flush()
                        }
                        return file
                    }

                    override fun onPostExecute(result: File) {
                        pdfView.fromFile(result).load()
                    }
                }

                downloadTask.execute()

                supportActionBar?.setTitle("Rekap Kehadiran")
                supportActionBar?.apply {
                    setBackgroundDrawable(ColorDrawable(Color.BLACK))
                }

                searchItem.isVisible = false
                micItem.isVisible = false

                val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                    duration = 250
                    addUpdateListener {
                        val value = it.animatedValue as Float
                        pdfView.scaleX = value
                        pdfView.scaleY = value
                    }
                }

                val visibilityAnimator = ObjectAnimator.ofFloat(flAbsensiViewPembimbing, "alpha", 0f, 1f).apply {
                    duration = 250
                }

                val animatorSet = AnimatorSet().apply {
                    playTogether(animator, visibilityAnimator)
                }

                flAbsensiViewPembimbing.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
                    override fun onLayoutChange(
                        view: View?,
                        left: Int,
                        top: Int,
                        right: Int,
                        bottom: Int,
                        oldLeft: Int,
                        oldTop: Int,
                        oldRight: Int,
                        oldBottom: Int
                    ) {
                        if (view?.visibility == View.GONE) {
                            pdfView.scaleX = 0f
                            pdfView.scaleY = 0f
                        }
                    }
                })

                window.statusBarColor = ContextCompat.getColor(context, R.color.black)

                flAbsensiViewPembimbing.visibility = View.VISIBLE
                animatorSet.start()
            }
        },20000)
    }

    fun dataRekap(context: Context, bulan:String, tahun:String){
        val request = object : StringRequest(
            Method.POST,cetak,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    cetakPDF(context, bulan, tahun)
                }else{
                    val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()

                            Toast.makeText(context, "Data rekap kehadiran tidak ada", Toast.LENGTH_SHORT).show()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","cekrekapSeluruhKehadiranPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("bulan",bulan)
                data.put("tahun",tahun)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    private fun refreshUp(){
        refreshPresensi.setColorSchemeColors(
            Color.parseColor("#5AA0FF")
        )
        refreshPresensi.setOnRefreshListener {
            Handler().postDelayed(Runnable {
                dataPresensi()
                jumlahDataPresensi()
                refreshPresensi.isRefreshing = false
            }, 2000)
        }
    }

    override fun onBackPressed() {
        if(lihat.equals("1")) {
            val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    imageView19.scaleX = value
                    imageView19.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(fLayout, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        fLayout.visibility = View.GONE
                    }

                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }

            supportActionBar?.apply {
                setBackgroundDrawable(
                    android.graphics.drawable.ColorDrawable(
                        ContextCompat.getColor(
                            this@PresensiActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true
            absensiItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Absensi")

            lihat = "0"
            animatorSet.start()
        }else if(lihat.equals("2")){
            val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    pdfViewPembimbing.scaleX = value
                    pdfViewPembimbing.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(fLayout, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        flAbsensiViewPembimbing.visibility = View.GONE
                    }

                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }

            supportActionBar?.apply {
                setBackgroundDrawable(
                    ColorDrawable(
                        ContextCompat.getColor(
                            this@PresensiActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true
            absensiItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Absensi")

            lihat = "0"
            animatorSet.start()
        }else{
            if (isTaskRoot) {
                var paket : Bundle? = intent.extras
                var notifikasi = paket?.getString("notifikasi").toString()

                val intent = Intent(this, HomePembimbingActivity::class.java)
                intent.putExtra("notifikasi", notifikasi)
                intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_SINGLE_TOP
                startActivity(intent)
                finish()

                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
            } else {
                super.onBackPressed()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
                finish()
            }
        }
    }

    override fun onSupportNavigateUp(): Boolean {
        if(lihat.equals("1")) {
            val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    imageView19.scaleX = value
                    imageView19.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(fLayout, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        fLayout.visibility = View.GONE
                    }

                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }

            supportActionBar?.apply {
                setBackgroundDrawable(
                    android.graphics.drawable.ColorDrawable(
                        ContextCompat.getColor(
                            this@PresensiActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true
            absensiItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Absensi")

            lihat = "0"
            animatorSet.start()
        }else if(lihat.equals("2")){
            val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    pdfViewPembimbing.scaleX = value
                    pdfViewPembimbing.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(fLayout, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        flAbsensiViewPembimbing.visibility = View.GONE
                    }

                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }

            supportActionBar?.apply {
                setBackgroundDrawable(
                    ColorDrawable(
                        ContextCompat.getColor(
                            this@PresensiActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true
            absensiItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Absensi")

            lihat = "0"
            animatorSet.start()
        }else{
            if (isTaskRoot) {
                var paket : Bundle? = intent.extras
                var notifikasi = paket?.getString("notifikasi").toString()

                val intent = Intent(this, HomePembimbingActivity::class.java)
                intent.putExtra("notifikasi", notifikasi)
                intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_SINGLE_TOP
                startActivity(intent)
                finish()

                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
            } else {
                finish()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
            }
        }

        return true
    }

    fun found(){
        lsForum.visibility = View.VISIBLE
        imageView9.visibility = View.GONE
    }

    fun notFound(){
        lsForum.visibility = View.GONE
        imageView9.visibility = View.VISIBLE
    }

    fun jumlahDataPresensi(){
        val request = object : StringRequest(
            Method.POST,presensi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("absensi")

                if(jumlah.equals("0")){
                    lsForum.visibility = View.GONE
                    imageView9.visibility = View.VISIBLE
                }else{
                    lsForum.visibility = View.VISIBLE
                    imageView9.visibility = View.GONE
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","jumlahViewPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun jumlahDataPresensi(kode:String){
        val request = object : StringRequest(
            Method.POST,presensi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("absensi")

                if(jumlah.equals("0")){
                    lsForum.visibility = View.GONE
                    imageView9.visibility = View.VISIBLE
                }else{
                    lsForum.visibility = View.VISIBLE
                    imageView9.visibility = View.GONE
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","jumlahViewPembimbingKode")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("kode",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun dataPresensi(){
        val request = object : StringRequest(
            Method.POST,presensi,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("kd_mhs",jsonObject.getString("KD_PST"))
                    frm.put("namaMhs",jsonObject.getString("NAMA_PST"))
                    frm.put("namaKmps",jsonObject.getString("NAMA_ASAL"))
                    frm.put("tanggal",jsonObject.getString("tanggal"))
                    frm.put("kehadiran",jsonObject.getString("KEHADIRAN"))
                    frm.put("check_in",jsonObject.getString("CHECK_IN"))
                    frm.put("check_out",jsonObject.getString("CHECK_OUT"))
                    frm.put("lokasi_masuk",jsonObject.getString("LOKASI_CHECK_IN"))
                    frm.put("lokasi_pulang",jsonObject.getString("LOKASI_CHECK_OUT"))
                    frm.put("keterangan",jsonObject.getString("KETERANGAN"))
                    frm.put("kegiatan",jsonObject.getString("KEGIATAN"))
                    frm.put("status",jsonObject.getString("STATUS"))
                    frm.put("surat",jsonObject.getString("surat"))
                    frm.put("srt",jsonObject.getString("srt"))
                    frm.put("lokSurat",jsonObject.getString("LOKASI_KIRIM_SURAT"))
                    frm.put("status_surat",jsonObject.getString("STATUS_SURAT"))
                    frm.put("kategori",jsonObject.getString("KATEGORI_ASAL"))
                    frm.put("profil",jsonObject.getString("url"))

                    daftarForum.add(frm)
                }
                forumPresensi.notifyDataSetChanged()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","viewPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun dataPresensi(kode: String){
        val request = object : StringRequest(
            Method.POST,presensi,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("kd_mhs",jsonObject.getString("KD_PST"))
                    frm.put("namaMhs",jsonObject.getString("NAMA_PST"))
                    frm.put("namaKmps",jsonObject.getString("NAMA_ASAL"))
                    frm.put("tanggal",jsonObject.getString("tanggal"))
                    frm.put("kehadiran",jsonObject.getString("KEHADIRAN"))
                    frm.put("check_in",jsonObject.getString("CHECK_IN"))
                    frm.put("check_out",jsonObject.getString("CHECK_OUT"))
                    frm.put("lokasi_masuk",jsonObject.getString("LOKASI_CHECK_IN"))
                    frm.put("lokasi_pulang",jsonObject.getString("LOKASI_CHECK_OUT"))
                    frm.put("keterangan",jsonObject.getString("KETERANGAN"))
                    frm.put("kegiatan",jsonObject.getString("KEGIATAN"))
                    frm.put("status",jsonObject.getString("STATUS"))
                    frm.put("surat",jsonObject.getString("surat"))
                    frm.put("srt",jsonObject.getString("srt"))
                    frm.put("lokSurat",jsonObject.getString("LOKASI_KIRIM_SURAT"))
                    frm.put("status_surat",jsonObject.getString("STATUS_SURAT"))
                    frm.put("kategori",jsonObject.getString("KATEGORI_ASAL"))
                    frm.put("profil",jsonObject.getString("url"))

                    daftarForum.add(frm)
                }
                forumPresensi.notifyDataSetChanged()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","viewPembimbingKode")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("kode",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun startLoading(){
        val inflater = this.layoutInflater
        val dialogView = inflater.inflate(R.layout.circle_loading,null)

        val builder = AlertDialog.Builder(this)
        builder.setView(dialogView)
        builder.setCancelable(false)
        isDialog = builder.create()
        isDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
        isDialog.show()
    }

    fun isDismiss(){
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                isDialog.dismiss()
            }
        },2000)
    }
}