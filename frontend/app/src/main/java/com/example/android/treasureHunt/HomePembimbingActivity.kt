package com.example.android.treasureHunt

import android.animation.Animator
import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.graphics.Color
import android.os.Bundle
import android.os.Handler
import android.util.Log
import android.view.LayoutInflater
import android.view.MenuItem
import android.view.View
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.core.view.isVisible
import androidx.fragment.app.FragmentManager
import androidx.fragment.app.FragmentTransaction
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.gms.maps.model.LatLng
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.pusher.client.Pusher
import com.pusher.client.PusherOptions
import com.pusher.client.connection.ConnectionEventListener
import com.pusher.client.connection.ConnectionState
import com.pusher.client.connection.ConnectionStateChange
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.activity_hunt_main.*
import kotlinx.android.synthetic.main.activity_main_pembimbing.*
import kotlinx.android.synthetic.main.activity_main_pembimbing.cardView2
import kotlinx.android.synthetic.main.activity_main_pembimbing.cardView5
import kotlinx.android.synthetic.main.activity_main_pembimbing.cardView9
import kotlinx.android.synthetic.main.activity_main_pembimbing.cr
import kotlinx.android.synthetic.main.activity_main_pembimbing.flFoto
import kotlinx.android.synthetic.main.activity_main_pembimbing.frameLayout
import kotlinx.android.synthetic.main.activity_main_pembimbing.imageView16
import kotlinx.android.synthetic.main.activity_main_pembimbing.imageView17
import kotlinx.android.synthetic.main.activity_main_pembimbing.profile_image
import kotlinx.android.synthetic.main.activity_main_pembimbing.textView10
import kotlinx.android.synthetic.main.activity_main_pembimbing.textView3
import kotlinx.android.synthetic.main.activity_main_pembimbing.textView8
import kotlinx.android.synthetic.main.activity_main_pembimbing.textView9
import kotlinx.android.synthetic.main.activity_onboarding_example1.*
import kotlinx.android.synthetic.main.fragment_maps_pembimbing.*
import layout.transitions.library.*
import org.json.JSONArray
import org.json.JSONObject
import java.util.*
import kotlin.collections.HashMap

class HomePembimbingActivity:AppCompatActivity(), View.OnClickListener, BottomNavigationView.OnNavigationItemSelectedListener {
    private lateinit var bottomNavigationView: BottomNavigationView
    lateinit var ft: FragmentTransaction
    lateinit var fragSistemPem:FragmentSistemPem
    lateinit var fragMapsPem:FragmentMapsPembimbing
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    val perizinan = "http://192.168.43.57/simaptapkl/public/service/perizinan.php"
    val user = "http://192.168.43.57/simaptapkl/public/service/akun.php"
    val absensi = "http://192.168.43.57/simaptapkl/public/service/absensi.php"
    val lokasi = "http://192.168.43.57/simaptapkl/public/service/logpos.php"
    val konfigurasi = "http://192.168.43.57/simaptapkl/public/service/konfigurasi.php"
    var lihat = "0"
    var zoom = "0"
    var cekKoneksi = "1"
    private lateinit var isDialog: AlertDialog
    lateinit var timer: Timer
    var cektimer = "0"
    var fragmenlatitude = ""
    var fragmenlongitude = ""
    var fragmenradius = ""
    var fragmenjudul = ""

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.notifikasi->{
                val intent = Intent(this, SuratActivity::class.java)
                startActivityForResult(intent,10)
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InRight())
            }

            R.id.cardView2->{
                val intent = Intent(this, AsalPesertaActivity::class.java)
                startActivity(intent)
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InRight())
            }

            R.id.cardView5->{
                val intent = Intent(this, TimPesertaActivity::class.java)
                startActivity(intent)
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InRight())
            }

            R.id.cardView22->{
                val intent = Intent(this, PresensiActivity::class.java)
                startActivityForResult(intent,11)
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InRight())
            }

            R.id.cardView50->{
                val intent = Intent(this, LogPosActivity::class.java)
                startActivity(intent)
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InRight())
            }

            R.id.cardView9->{
                val intent = Intent(this, KosongActivity::class.java)
                startActivity(intent)
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InRight())
            }

            R.id.imageView17->{
                lihat = "0"
                notifikasi.visibility = View.VISIBLE
//                imageView15.setImageResource(R.drawable.notifikasi)
                textView157.visibility = View.VISIBLE
                cardView100.visibility = View.VISIBLE
                val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                    duration = 250
                    addUpdateListener {
                        val value = it.animatedValue as Float
                        imageView16.scaleX = value
                        imageView16.scaleY = value
                    }
                }

                val visibilityAnimator = ObjectAnimator.ofFloat(flFoto, "alpha", 1f, 0f).apply {
                    duration = 250
                }

                val animatorSet = AnimatorSet().apply {
                    playTogether(animator, visibilityAnimator)
                    addListener(object : Animator.AnimatorListener {
                        override fun onAnimationStart(animation: Animator?) {}
                        override fun onAnimationEnd(animation: Animator?) {
                            flFoto.visibility = View.GONE
                        }
                        override fun onAnimationCancel(animation: Animator?) {}
                        override fun onAnimationRepeat(animation: Animator?) {}
                    })
                }
                animatorSet.start()

                window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            }
            
            R.id.profile_image->{
                foto()

                lihat = "1"
                notifikasi.visibility = View.GONE
//                imageView15.setImageResource(0)
                textView157.visibility = View.GONE
                cardView100.visibility = View.GONE

                val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                    duration = 250
                    addUpdateListener {
                        val value = it.animatedValue as Float
                        imageView16.scaleX = value
                        imageView16.scaleY = value
                    }
                }

                val visibilityAnimator = ObjectAnimator.ofFloat(flFoto, "alpha", 0f, 1f).apply {
                    duration = 250
                }

                val animatorSet = AnimatorSet().apply {
                    playTogether(animator, visibilityAnimator)
                }

                flFoto.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                            imageView16.scaleX = 0f
                            imageView16.scaleY = 0f
                        }
                    }
                })

                window.statusBarColor = ContextCompat.getColor(this, R.color.black)

                flFoto.visibility = View.VISIBLE
                animatorSet.start()
            }
        }
    }

    override fun onNavigationItemSelected(item: MenuItem): Boolean {
        when(item?.itemId){
            R.id.btnBeranda->{
                frameLayout.visibility = View.GONE
                notifikasi.visibility = View.VISIBLE
                window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
                window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_VISIBLE
//                imageView15.setImageResource(R.drawable.notifikasi)
                jumlahIzinWaiting()
                akun()

                profile_image.visibility = View.VISIBLE
                cardView2.visibility = View.VISIBLE
                cardView5.visibility = View.VISIBLE
                cardView22.visibility = View.VISIBLE
                cardView50.visibility = View.VISIBLE
                refreshHomePembimbing.isEnabled = true

                zoom = "1"
            }

            R.id.btnLokasi->{
                ft = supportFragmentManager.beginTransaction()
                ft.replace(R.id.frameLayout,fragMapsPem).commit()
                frameLayout.setBackgroundColor(Color.argb(255,255,255,255))
                window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
                window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
                frameLayout.visibility = View.VISIBLE
                notifikasi.visibility = View.GONE
//                imageView15.setImageResource(0)
                textView157.visibility = View.GONE
                cardView100.visibility = View.GONE

                profile_image.visibility = View.GONE
                cardView2.visibility = View.GONE
                cardView5.visibility = View.GONE
                cardView22.visibility = View.GONE
                cardView50.visibility = View.GONE
                refreshHomePembimbing.isEnabled = false

                val bundle = Bundle()
                var paket : Bundle? = intent.extras
                var notifikasi = paket?.getString("notifikasi").toString()

                if(notifikasi.equals("1")){
                    bundle.putString("latitude", fragmenlatitude)
                    bundle.putString("longitude", fragmenlongitude)
                    bundle.putString("radius", fragmenradius)
                    bundle.putString("title", fragmenjudul)
                    bundle.putString("kondisi", "semua")
                }else{
                    val lat = paket?.getString("latitude")
                    val long = paket?.getString("longitude")
                    val rad = paket?.getString("radius")
                    val judul = paket?.getString("judul")
                    bundle.putString("latitude", lat)
                    bundle.putString("longitude", long)
                    bundle.putString("radius", rad)
                    bundle.putString("title", judul)
                    bundle.putString("kondisi", "semua")
                }

                fragMapsPem.arguments = bundle
            }

            R.id.btnSistem->{
                if(cektimer.equals("1")){
                    timer.cancel()
                }

                ft = supportFragmentManager.beginTransaction()
                ft.replace(R.id.frameLayout,fragSistemPem).commit()
                frameLayout.setBackgroundColor(Color.argb(255,255,255,255))
                window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
                window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
                frameLayout.visibility = View.VISIBLE
                notifikasi.visibility = View.GONE
//                imageView15.setImageResource(0)
                textView157.visibility = View.GONE
                cardView100.visibility = View.GONE

                profile_image.visibility = View.GONE
                cardView2.visibility = View.GONE
                cardView5.visibility = View.GONE
                cardView22.visibility = View.GONE
                cardView50.visibility = View.GONE
                refreshHomePembimbing.isEnabled = false

                zoom = "1"
            }
        }
        return true
    }

    fun hideBottomNavigationItemBeranda(itemId: Int) {
        val menu = btnNavPem.menu
        val item = menu.findItem(itemId)
        item?.isVisible = false
    }

    fun hideBottomNavigationItemLokasi(itemId: Int) {
        val menu = btnNavPem.menu
        val item = menu.findItem(itemId)
        item?.isVisible = false
    }

    fun hideBottomNavigationItemPengaturan(itemId: Int) {
        val menu = btnNavPem.menu
        val item = menu.findItem(itemId)
        item?.isVisible = false
    }

    fun showBottomNavigationItemBeranda(itemId: Int) {
        val menu = btnNavPem.menu
        val item = menu.findItem(itemId)
        item?.isVisible = true
    }

    fun showBottomNavigationItemLokasi(itemId: Int) {
        val menu = btnNavPem.menu
        val item = menu.findItem(itemId)
        item?.isVisible = true
    }

    fun showBottomNavigationItemPengaturan(itemId: Int) {
        val menu = btnNavPem.menu
        val item = menu.findItem(itemId)
        item?.isVisible = true
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main_pembimbing)
        supportActionBar?.hide()
        btnNavPem.setOnNavigationItemSelectedListener(this)
        bottomNavigationView = findViewById(R.id.btnNavPem)

        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
        notifikasi.setOnClickListener(this)
        profile_image.setOnClickListener(this)
        cr.setOnClickListener(this)
        imageView17.setOnClickListener(this)
        cardView2.setOnClickListener(this)
        cardView5.setOnClickListener(this)
        cardView22.setOnClickListener(this)
        cardView50.setOnClickListener(this)
        cardView9.setOnClickListener(this)

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

        var paket : Bundle? = intent.extras
        var notifikasi = paket?.getString("notifikasi").toString()

        if(notifikasi.equals("1")){
            fragmenlokasi()
        }

        val service = Intent(this, PembimbingService::class.java)
        startService(service)

        fragSistemPem = FragmentSistemPem()
        fragMapsPem = FragmentMapsPembimbing()
        jumlahIzinWaiting()
        akun()
        jumlahAbsensi()
        refreshUp()
    }

    fun fragmenlokasi(){
        val request = object : StringRequest(
            Method.POST,konfigurasi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val lat = jsonObject.getString("LATITUDE_KONF")
                val long = jsonObject.getString("LONGITUDE_KONF")
                val rad = jsonObject.getString("RADIUS_KONF")
                val judul = jsonObject.getString("JUDUL_RADIUS")

                fragmenlatitude = lat
                fragmenlongitude = long
                fragmenradius = rad
                fragmenjudul = judul
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                data.put("pilihan","lokasi")

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    override fun onBackPressed() {
        if(lihat.equals("1")){
            lihat = "0"
            val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    imageView16.scaleX = value
                    imageView16.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(flFoto, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        flFoto.visibility = View.GONE
                    }
                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }
            animatorSet.start()

            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
        }else{
            super.onBackPressed()
        }
    }

    private fun refreshUp(){
        refreshHomePembimbing.setColorSchemeColors(
            Color.parseColor("#5AA0FF")
        )
        refreshHomePembimbing.setOnRefreshListener {
            Handler().postDelayed(Runnable {
                jumlahAbsensi()
                jumlahIzinWaiting()
                akun()
                refreshHomePembimbing.isRefreshing = false
            }, 2000)
        }
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)
        if(requestCode == 10){
            var jumlah = data?.extras?.getString("proses")
            if(jumlah.equals("0")){
//                imageView15.visibility = View.GONE
                textView157.visibility = View.GONE
                cardView100.visibility = View.GONE
                jumlahAbsensi()
            }else{
//                imageView15.visibility = View.VISIBLE
                textView157.visibility = View.VISIBLE
                cardView100.visibility = View.VISIBLE
                jumlahAbsensi()
            }
        }else{
            jumlahAbsensi()
        }
    }

    fun foto(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val fotoProfil = jsonObject.getString("url")

                Picasso.get().load(fotoProfil).into(imageView16)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","homePembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun jumlahAbsensi(){
        homeHadir()
        homeIzin()
        homePeserta()
    }

    fun homeHadir(){
        val request = object : StringRequest(
            Method.POST,absensi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val hadir = jsonObject.getString("hadir")

                textView8.setText(hadir)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","homeHadir")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun homeIzin(){
        val request = object : StringRequest(
            Method.POST,absensi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val izin = jsonObject.getString("izin")

                textView9.setText(izin)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","homeIzin")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun homePeserta(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val peserta = jsonObject.getString("peserta")

                textView10.setText(peserta)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","homePeserta")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun akun(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val karyawan = jsonObject.getString("NAMA_KAWAN")
                val fotoProfil = jsonObject.getString("url")

                textView3.setText(karyawan)
                Picasso.get().load(fotoProfil).into(profile_image)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","homePembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun jumlahIzinWaiting(){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("jumlah")

                if(jumlah.equals("0")){
//                    imageView15.visibility = View.GONE
                    textView157.visibility = View.GONE
                    cardView100.visibility = View.GONE
                }else{
//                    imageView15.visibility = View.VISIBLE
                    textView157.visibility = View.VISIBLE
                    cardView100.visibility = View.VISIBLE
                    textView157.setText(jumlah)
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
}