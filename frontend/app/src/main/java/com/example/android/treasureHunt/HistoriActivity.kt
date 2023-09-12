package com.example.android.treasureHunt

import android.Manifest
import android.animation.Animator
import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.app.Activity
import android.app.AlertDialog
import android.app.DownloadManager
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.content.pm.PackageManager
import android.graphics.Color
import android.graphics.drawable.ColorDrawable
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.net.Uri
import android.os.AsyncTask
import android.os.Bundle
import android.os.Environment
import android.os.Handler
import android.speech.RecognizerIntent
import android.view.Menu
import android.view.MenuItem
import android.view.View
import com.github.barteksc.pdfviewer.PDFView
import androidx.appcompat.widget.SearchView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.LinearLayoutManager
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.activity_histori.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import org.json.JSONArray
import org.json.JSONObject
import java.io.File
import java.io.FileOutputStream
import java.net.URL


class HistoriActivity:AppCompatActivity(), LocationListener {
    val daftarForum = mutableListOf<HashMap<String,String>>()
    lateinit var forumHistori : AdapterHistori
    val histori = "http://192.168.43.57/simaptapkl/public/service/histori.php"
    val cetak = "http://192.168.43.57/simaptapkl/public/service/cetak.php"
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    var SPEECH_REQUEST_CODE = 0
    private lateinit var searchView: SearchView
    lateinit var searchItem: MenuItem
    lateinit var micItem: MenuItem
    lateinit var absensiItem: MenuItem
    lateinit var itemDownload: MenuItem
    var lihat = "0"
    var cekKoneksi = "1"
    var download = "0"
    lateinit var alertDialog: AlertDialog
    private lateinit var isDialog: AlertDialog
    private lateinit var locationManager: LocationManager
    var proses = ""
    var namaFile = ""
    var cekGPS = "1"
    lateinit var isInternet: AlertDialog
    lateinit var isGPS: AlertDialog
    lateinit var isInternetGPS: AlertDialog
    private var isActivityRunning = false

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_histori)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.setTitle("Absensi")
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
        locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager
        forumHistori = AdapterHistori(daftarForum, this)
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        lsForum.layoutManager = LinearLayoutManager(this)
        lsForum.adapter = forumHistori

        dataHistori()
        jumlahDataHistori()
        enableUserLocation()
        refreshUp()

        val networkConnection = NetworkConnection(applicationContext)
        networkConnection.observe(this, { isConnected ->
            if (isConnected) {
                if(cekKoneksi.equals("0")){
                    if(cekGPS.equals("0")){
                        isDismissInternetGPS()
                        startLoadingGPS()

                        cekKoneksi = "1"
                    }else{
                        isDismissInternet()
                        cekKoneksi = "1"
                    }
                }else{
                    cekKoneksi = "1"
                }
            } else {
                if(cekGPS.equals("0")){
                    cekKoneksi = "0"
                    isDismissGPS()
                    startLoadingInternetGPS()
                }else{
                    cekKoneksi = "0"
                    startLoadingInternet()
                }
            }
        })

        isActivityRunning = true
    }

    override fun onDestroy() {
        super.onDestroy()
        isActivityRunning = false
    }

    fun startLoadingInternet(){
        val inflater = this.layoutInflater
        val dialogView = inflater.inflate(R.layout.circle_loading_internet,null)

        val builder = AlertDialog.Builder(this)
        builder.setView(dialogView)
        builder.setCancelable(false)
        isInternet = builder.create()
        isInternet.window?.setBackgroundDrawableResource(android.R.color.transparent)
        isInternet.show()
    }

    fun isDismissInternet(){
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                isInternet.dismiss()
            }
        },2000)
    }

    fun startLoadingGPS(){
        val inflater = this.layoutInflater
        val dialogView = inflater.inflate(R.layout.circle_loading_gps,null)

        val builder = AlertDialog.Builder(this)
        builder.setView(dialogView)
        builder.setCancelable(false)
        isGPS = builder.create()
        isGPS.window?.setBackgroundDrawableResource(android.R.color.transparent)
        isGPS.show()
    }

    fun isDismissGPS(){
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                isGPS.dismiss()
            }
        },2000)
    }

    fun startLoadingInternetGPS(){
        val inflater = this.layoutInflater
        val dialogView = inflater.inflate(R.layout.circle_loading_internet_gps,null)

        val builder = AlertDialog.Builder(this)
        builder.setView(dialogView)
        builder.setCancelable(false)
        isInternetGPS = builder.create()
        isInternetGPS.window?.setBackgroundDrawableResource(android.R.color.transparent)
        isInternetGPS.show()
    }

    fun isDismissInternetGPS(){
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                isInternetGPS.dismiss()
            }
        },2000)
    }

    private fun refreshUp(){
        refreshHistori.setColorSchemeColors(
            Color.parseColor("#5AA0FF")
        )
        refreshHistori.setOnRefreshListener {
            Handler().postDelayed(Runnable {
                dataHistori()
                jumlahDataHistori()
                refreshHistori.isRefreshing = false
            }, 2000)
        }
    }

    override fun onLocationChanged(location: Location) {

    }

    override fun onStatusChanged(provider: String?, status: Int, extras: Bundle?) {
        // handle location status changes
    }

    override fun onProviderEnabled(provider: String) {
        // handle provider enabled
        if(cekGPS.equals("0")){
            if(cekKoneksi.equals("1")){
                if (isActivityRunning) {
                    isDismissGPS()
                }
            }else{
                isDismissInternetGPS()
                startLoadingInternet()
            }
        }

        cekGPS = "1"
    }

    override fun onProviderDisabled(provider: String) {
        // handle provider disabled
        if(cekGPS.equals("1")){
            if(cekKoneksi.equals("1")){
                if (isActivityRunning) {
                    startLoadingGPS()
                }
            }else{
                isDismissInternet()
                startLoadingInternetGPS()
            }
        }

        cekGPS = "0"
    }

    private fun enableUserLocation(){
        // check for location permission
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED) {

            // check if GPS is enabled
            if (locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
                // request location updates
                locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0f, this)
            }
        }
    }

    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.menu_aksi_histori, menu)
        searchItem = menu?.findItem(R.id.searchItem)!!
        micItem = menu?.findItem(R.id.micItem)!!
        absensiItem = menu?.findItem(R.id.itemAbsensi)!!
        itemDownload = menu?.findItem(R.id.downloadItem)!!
        itemDownload.isVisible = false
        searchView = searchItem?.actionView as SearchView

        searchView.queryHint = "Search Here ..."
        searchView.setOnQueryTextListener(object : SearchView.OnQueryTextListener {
            override fun onQueryTextSubmit(query: String?): Boolean {
                return false
            }

            override fun onQueryTextChange(newText: String?): Boolean {
                forumHistori.filter.filter(newText)
                return true
            }
        })

        return super.onCreateOptionsMenu(menu)
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when(item?.itemId){
            R.id.downloadItem->{
                if(download.equals("1")){
                    downloadRekapAbsensi(namaFile)
                }
            }
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
                lihat = "2"
                download = "1"
                rekapAbsensi(this)
            }
        }
        return super.onOptionsItemSelected(item)
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

            val visibilityAnimator = ObjectAnimator.ofFloat(flSurat, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        flSurat.visibility = View.GONE
                    }

                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }

            supportActionBar?.apply {
                setBackgroundDrawable(
                    ColorDrawable(
                        ContextCompat.getColor(
                            this@HistoriActivity,
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
                    pdfView.scaleX = value
                    pdfView.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(flSurat, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        flAbsensiView.visibility = View.GONE
                    }

                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }

            supportActionBar?.apply {
                setBackgroundDrawable(
                    ColorDrawable(
                        ContextCompat.getColor(
                            this@HistoriActivity,
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
            download = "0"
            itemDownload.isVisible = false
            animatorSet.start()
        }else{
            if (isTaskRoot) {
                var paket : Bundle? = intent.extras
                var notifikasi = paket?.getString("notifikasi").toString()

                val intent = Intent(this, HuntMainActivity::class.java)
                intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_SINGLE_TOP
                intent.putExtra("notifikasi", notifikasi)
                startActivity(intent)
                finish()

                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
            }else{
                proses = "1"
                finish()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
                locationManager.removeUpdates(this)
            }
        }

        return true
    }

    override fun onBackPressed() {
        if(lihat.equals("1")){
            val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    imageView19.scaleX = value
                    imageView19.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(flSurat, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        flSurat.visibility = View.GONE
                    }
                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }

            searchItem.isVisible = true
            micItem.isVisible = true
            absensiItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Absensi")

            supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(ContextCompat.getColor(this@HistoriActivity, R.color.statusBarColor)))
            }

            lihat = "0"
            animatorSet.start()
        }else if(lihat.equals("2")){
            val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    pdfView.scaleX = value
                    pdfView.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(flSurat, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        flAbsensiView.visibility = View.GONE
                    }

                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }

            supportActionBar?.apply {
                setBackgroundDrawable(
                    ColorDrawable(
                        ContextCompat.getColor(
                            this@HistoriActivity,
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
            download = "0"
            itemDownload.isVisible = false
            animatorSet.start()
        }else{
            if (isTaskRoot) {
                var paket : Bundle? = intent.extras
                var notifikasi = paket?.getString("notifikasi").toString()

                val intent = Intent(this, HuntMainActivity::class.java)
                intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_SINGLE_TOP
                intent.putExtra("notifikasi", notifikasi)
                startActivity(intent)
                finish()

                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
            }else{
                proses = "1"
                super.onBackPressed()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
                locationManager.removeUpdates(this)
            }
        }
    }

    override fun finish() {
        var intent = Intent()

        if(!proses.equals("")){
            intent.putExtra("proses", proses)
            setResult(Activity.RESULT_OK,intent)
        }

        super.finish()
    }

    fun found(){
        lsForum.visibility = View.VISIBLE
        imageView12.visibility = View.GONE
    }

    fun notFound(){
        lsForum.visibility = View.GONE
        imageView12.visibility = View.VISIBLE
    }

    fun rekapAbsensi(context: Context){
        val request = object : StringRequest(
            Method.POST,cetak,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val file = jsonObject.getString("file")

                if(!file.isEmpty()){
                    val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            itemDownload.isVisible = true
                            namaFile = file

                            val pdfView = findViewById<PDFView>(R.id.pdfView)
                            val urlLihat = "http://192.168.43.57/simaptapkl/public/service/generate/"+file

                            val downloadTask = object : AsyncTask<Void, Void, File>() {
                                override fun doInBackground(vararg params: Void?): File {
                                    val inputStream = URL(urlLihat).openStream()
                                    val file = File(cacheDir, "file.pdf")
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

                            supportActionBar?.setTitle("Absensi Kehadiran")
                            supportActionBar?.apply {
                                setBackgroundDrawable(ColorDrawable(Color.BLACK))
                            }

                            searchItem.isVisible = false
                            micItem.isVisible = false
                            absensiItem.isVisible = false

                            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                                duration = 250
                                addUpdateListener {
                                    val value = it.animatedValue as Float
                                    pdfView.scaleX = value
                                    pdfView.scaleY = value
                                }
                            }

                            val visibilityAnimator = ObjectAnimator.ofFloat(flAbsensiView, "alpha", 0f, 1f).apply {
                                duration = 250
                            }

                            val animatorSet = AnimatorSet().apply {
                                playTogether(animator, visibilityAnimator)
                            }

                            flAbsensiView.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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

                            flAbsensiView.visibility = View.VISIBLE
                            animatorSet.start()
                        }
                    },3000)
                }

            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","rekapKehadiran")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun downloadRekapAbsensi(file:String){
        val url = "http://192.168.43.57/simaptapkl/public/service/generate/"+file
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        var downloadmanager = getSystemService(Context.DOWNLOAD_SERVICE) as DownloadManager
        val link = Uri.parse(url)
        var request = DownloadManager.Request(link)
        request.setAllowedNetworkTypes(DownloadManager.Request.NETWORK_MOBILE or DownloadManager.Request.NETWORK_WIFI)
            .setMimeType("application/pdf")
            .setAllowedOverRoaming(false)
            .setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED)
            .setTitle(file)
            .setDescription("Downloading...")
            .setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, File.separator+file+".pdf")

        val loading = LoadingDialog(this)
        loading.startLoading()
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                loading.isDismiss()
                downloadmanager.enqueue(request)
                download = "0"
            }
        },3000)
    }

    fun jumlahDataHistori(){
        val request = object : StringRequest(
            Method.POST,histori,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("jumlah")

                if(jumlah.equals("0")){
                    lsForum.visibility = View.GONE
                    imageView12.visibility = View.VISIBLE
                }else{
                    lsForum.visibility = View.VISIBLE
                    imageView12.visibility = View.GONE
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","jumlahHistori")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun dataHistori(){
        val request = object : StringRequest(
            Method.POST,histori,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("status",jsonObject.getString("STATUS"))
                    frm.put("kehadiran",jsonObject.getString("KEHADIRAN"))
                    frm.put("check_in",jsonObject.getString("CHECK_IN"))
                    frm.put("check_out",jsonObject.getString("CHECK_OUT"))
                    frm.put("lokasi_masuk",jsonObject.getString("LOKASI_CHECK_IN"))
                    frm.put("lokasi_pulang",jsonObject.getString("LOKASI_CHECK_OUT"))
                    frm.put("keterangan",jsonObject.getString("KETERANGAN"))
                    frm.put("tanggal",jsonObject.getString("tanggal"))
                    frm.put("kegiatan",jsonObject.getString("KEGIATAN"))
                    frm.put("srt",jsonObject.getString("srt"))
                    frm.put("lokSurat",jsonObject.getString("LOKASI_KIRIM_SURAT"))
                    frm.put("surat",jsonObject.getString("url"))

                    daftarForum.add(frm)
                }
                forumHistori.notifyDataSetChanged()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                data.put("pilihan","view")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        if (requestCode == SPEECH_REQUEST_CODE && resultCode == Activity.RESULT_OK) {
            val spokenText: String? =
                data?.getStringArrayListExtra(RecognizerIntent.EXTRA_RESULTS).let { results ->
                    results?.get(0)
                }
            // Do something with spokenText
            searchView.setQuery(spokenText, false)
        }
        super.onActivityResult(requestCode, resultCode, data)
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