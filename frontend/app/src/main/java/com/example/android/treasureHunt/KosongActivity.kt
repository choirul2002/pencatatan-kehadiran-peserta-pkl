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
import android.location.Location
import android.os.Bundle
import android.os.Handler
import android.speech.RecognizerIntent
import android.view.Menu
import android.view.MenuItem
import android.view.View
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.SearchView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.LinearLayoutManager
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.textfield.TextInputEditText
import kotlinx.android.synthetic.main.activity_kosong.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import mumayank.com.airlocationlibrary.AirLocation
import org.json.JSONArray
import org.json.JSONObject
import java.text.SimpleDateFormat
import java.util.*
import kotlin.collections.HashMap

class KosongActivity: AppCompatActivity() {
    val daftarForum = mutableListOf<HashMap<String,String>>()
    val user = "http://192.168.43.57/simaptapkl/public/service/akun.php"
    lateinit var forumKosong: AdapterKosong
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    var lihat = "0"
    lateinit var searchView: SearchView
    lateinit var isDialog: AlertDialog
    lateinit var searchItem: MenuItem
    lateinit var micItem: MenuItem
    var SPEECH_REQUEST_CODE = 30
    var cekKoneksi = "1"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_kosong)

        forumKosong = AdapterKosong(daftarForum, this)
        lsForum.layoutManager = LinearLayoutManager(this)
        lsForum.adapter = forumKosong

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.setTitle("Presensi Kosong")
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)

        refreshUp()
        hari()

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
                forumKosong.filter.filter(newText)
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
        }
        return super.onOptionsItemSelected(item)
    }

    fun found(){
        lsForum.visibility = View.VISIBLE
        imageView9.visibility = View.GONE
    }
    //
    fun notFound(){
        lsForum.visibility = View.GONE
        imageView9.visibility = View.VISIBLE
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
                            this@KosongActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Presensi Kosong")

            lihat = "0"
            animatorSet.start()
        }else{
            super.onBackPressed()
            val transitions = Transitions(this)
            transitions.setAnimation(Fade().InLeft())
            finish()
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
                            this@KosongActivity,
                            R.color.statusBarColor
                        )
                    )
                )
            }
            searchItem.isVisible = true
            micItem.isVisible = true

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Presensi Kosong")

            lihat = "0"
            animatorSet.start()
        }else{
            finish()
            val transitions = Transitions(this)
            transitions.setAnimation(Fade().InLeft())
        }

        return true
    }

    private fun refreshUp(){
        var paket : Bundle? = intent.extras
        var perpindahan = paket?.getString("perpindahan").toString()
        var kodemahasiswa = paket?.getString("kodemahasiswa").toString()
        if(perpindahan.equals("1")){
            refreshKosong.setColorSchemeColors(
                Color.parseColor("#5AA0FF")
            )
            refreshKosong.setOnRefreshListener {
                Handler().postDelayed(Runnable {
                    dataKosong(kodemahasiswa)
                    jumlahDataKosong(kodemahasiswa)
                    refreshKosong.isRefreshing = false
                }, 2000)
            }
        }else{
            refreshKosong.setColorSchemeColors(
                Color.parseColor("#5AA0FF")
            )
            refreshKosong.setOnRefreshListener {
                Handler().postDelayed(Runnable {
                    dataKosong()
                    jumlahDataKosong()
                    refreshKosong.isRefreshing = false
                }, 2000)
            }
        }
    }

    fun hari(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("0")){
                    lsForum.visibility = View.GONE
                    imageView9.visibility = View.VISIBLE
                }else{
                    var paket : Bundle? = intent.extras
                    var perpindahan = paket?.getString("perpindahan").toString()
                    var kodemahasiswa = paket?.getString("kodemahasiswa").toString()
                    if(perpindahan.equals("1")){
                        dataKosong(kodemahasiswa)
                        jumlahDataKosong(kodemahasiswa)
                    }else{
                        dataKosong()
                        jumlahDataKosong()
                    }
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","cekhari")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun jumlahDataKosong(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("peserta")

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
                data.put("pilihan","jumlahDataKosong")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun jumlahDataKosong(kode:String){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("peserta")

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
                data.put("pilihan","jumlahDataKosongKode")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("kode",kode)
                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun dataKosong(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("kodeMhs",jsonObject.getString("KD_AKUN"))
                    frm.put("namaMhs",jsonObject.getString("NAMA_PST"))
                    frm.put("namaKmps",jsonObject.getString("NAMA_ASAL"))
                    frm.put("tanggal",jsonObject.getString("tanggal"))
                    frm.put("wa",jsonObject.getString("NOHP_PST"))
                    frm.put("kategori",jsonObject.getString("KATEGORI_ASAL"))
                    frm.put("profil",jsonObject.getString("url"))

                    daftarForum.add(frm)
                }
                forumKosong.notifyDataSetChanged()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","viewKosong")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun dataKosong(kode:String){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("kodeMhs",jsonObject.getString("KD_AKUN"))
                    frm.put("namaMhs",jsonObject.getString("NAMA_PST"))
                    frm.put("namaKmps",jsonObject.getString("NAMA_ASAL"))
                    frm.put("tanggal",jsonObject.getString("tanggal"))
                    frm.put("wa",jsonObject.getString("NOHP_PST"))
                    frm.put("kategori",jsonObject.getString("KATEGORI_ASAL"))
                    frm.put("profil",jsonObject.getString("url"))

                    daftarForum.add(frm)
                }
                forumKosong.notifyDataSetChanged()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","viewKosongKode")
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