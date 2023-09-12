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
import android.os.Bundle
import android.os.Handler
import android.speech.RecognizerIntent
import android.view.Menu
import android.view.MenuItem
import android.view.View
import android.widget.PopupMenu
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.SearchView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.LinearLayoutManager
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.activity_surat.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import org.json.JSONArray
import org.json.JSONObject

class SuratActivity: AppCompatActivity() {
    val perizinan = "http://192.168.43.57/simaptapkl/public/service/perizinan.php"
    val daftarForum = mutableListOf<HashMap<String,String>>()
    lateinit var forumIzinPembimbing: AdapterIzinPembimbing
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    private lateinit var searchView: SearchView
    var SPEECH_REQUEST_CODE = 0
    var jumlahIzin = ""
    var lihat = "0"
    lateinit var searchItem: MenuItem
    lateinit var micItem: MenuItem
    lateinit var alertDialog: AlertDialog
    private lateinit var isDialog: AlertDialog
    var cekKoneksi = "1"
    var perpindahan = "0"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_surat)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.setTitle("Ketidakhadiran")

        forumIzinPembimbing = AdapterIzinPembimbing(daftarForum, this)
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
        lsForum.layoutManager = LinearLayoutManager(this)
        lsForum.adapter = forumIzinPembimbing

        var paket : Bundle? = intent.extras
        perpindahan = paket?.getString("perpindahan").toString()
        var kodemahasiswa = paket?.getString("kodemahasiswa").toString()
        if(perpindahan.equals("1")){
            dataIzinPembimbing(kodemahasiswa)
            jumlahIzinWaiting(kodemahasiswa)
        }else{
            dataIzinPembimbing()
            jumlahIzinWaiting()
        }

        refreshUp()

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

    private fun refreshUp(){
        var paket : Bundle? = intent.extras
        var perpindahan = paket?.getString("perpindahan").toString()
        var kodemahasiswa = paket?.getString("kodemahasiswa").toString()
        if(perpindahan.equals("1")){
            refreshIzinPembimbing.setColorSchemeColors(
                Color.parseColor("#5AA0FF")
            )
            refreshIzinPembimbing.setOnRefreshListener {
                Handler().postDelayed(Runnable {
                    dataIzinPembimbing(kodemahasiswa)
                    jumlahIzinWaiting(kodemahasiswa)
                    refreshIzinPembimbing.isRefreshing = false
                }, 2000)
            }
        }else{
            refreshIzinPembimbing.setColorSchemeColors(
                Color.parseColor("#5AA0FF")
            )
            refreshIzinPembimbing.setOnRefreshListener {
                Handler().postDelayed(Runnable {
                    dataIzinPembimbing()
                    jumlahIzinWaiting()
                    refreshIzinPembimbing.isRefreshing = false
                }, 2000)
            }
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
                            this@SuratActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Ketidakhadiran")

            lihat = "0"
            animatorSet.start()
        }else if(lihat.equals("2")){
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
                            this@SuratActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Ketidakhadiran")

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
                var intent = Intent()
                intent.putExtra("proses", jumlahIzin)
                setResult(Activity.RESULT_OK,intent)

                super.onBackPressed()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
                finish()
            }
        }
    }

    override fun onSupportNavigateUp(): Boolean {
        if(lihat.equals("1")){
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
                            this@SuratActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Ketidakhadiran")

            lihat = "0"
            animatorSet.start()
        }else if(lihat.equals("2")){
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
                            this@SuratActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Ketidakhadiran")

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
                var intent = Intent()
                intent.putExtra("proses", jumlahIzin)
                setResult(Activity.RESULT_OK,intent)

                finish()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
            }
        }

        return true
    }

    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.menu_aksi_izin, menu)
        searchItem = menu?.findItem(R.id.searchItem)!!
        micItem = menu?.findItem(R.id.micItem)!!
        searchView = searchItem?.actionView as SearchView

        searchView.queryHint = "Search Here ..."
        searchView.setOnQueryTextListener(object : SearchView.OnQueryTextListener {
            override fun onQueryTextSubmit(query: String?): Boolean {
                return false
            }

            override fun onQueryTextChange(newText: String?): Boolean {
                forumIzinPembimbing.filter.filter(newText)
                return true
            }
        })

        return super.onCreateOptionsMenu(menu)
    }

    fun jumlahIzinWaiting(){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("jumlah")

                if(jumlah.equals("0")){
                    lsForum.visibility = View.GONE
                    imageView9.visibility = View.VISIBLE
                    jumlahIzin = jumlah
                }else{
                    lsForum.visibility = View.VISIBLE
                    imageView9.visibility = View.GONE
                    jumlahIzin = jumlah
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","jumlahIzinWaiting")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun jumlahIzinWaiting(kode:String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("jumlah")

                if(jumlah.equals("0")){
                    lsForum.visibility = View.GONE
                    imageView9.visibility = View.VISIBLE
                    jumlahIzin = jumlah
                }else{
                    lsForum.visibility = View.VISIBLE
                    imageView9.visibility = View.GONE
                    jumlahIzin = jumlah
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","jumlahIzinWaitingKode")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("kode",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
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
        }
        return super.onOptionsItemSelected(item)
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

    fun notFound(){
        lsForum.visibility = View.GONE
        imageView9.visibility = View.VISIBLE
    }

    fun found(){
        lsForum.visibility = View.VISIBLE
        imageView9.visibility = View.GONE
    }

    fun dataIzinPembimbing(){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("kd_peserta",jsonObject.getString("KD_AKUN"))
                    frm.put("peserta",jsonObject.getString("NAMA_PST"))
                    frm.put("asal",jsonObject.getString("NAMA_ASAL"))
                    frm.put("tanggal",jsonObject.getString("tgl"))
                    frm.put("foto",jsonObject.getString("foto"))
                    frm.put("surat",jsonObject.getString("surat"))
                    frm.put("idIzin",jsonObject.getString("ID"))
                    frm.put("kategori",jsonObject.getString("KATEGORI_ASAL"))

                    daftarForum.add(frm)
                }
                forumIzinPembimbing.notifyDataSetChanged()
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

    fun dataIzinPembimbing(kode:String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("kd_peserta",jsonObject.getString("KD_AKUN"))
                    frm.put("peserta",jsonObject.getString("NAMA_PST"))
                    frm.put("asal",jsonObject.getString("NAMA_ASAL"))
                    frm.put("tanggal",jsonObject.getString("tgl"))
                    frm.put("foto",jsonObject.getString("foto"))
                    frm.put("surat",jsonObject.getString("surat"))
                    frm.put("idIzin",jsonObject.getString("ID"))
                    frm.put("kategori",jsonObject.getString("KATEGORI_ASAL"))

                    daftarForum.add(frm)
                }
                forumIzinPembimbing.notifyDataSetChanged()
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