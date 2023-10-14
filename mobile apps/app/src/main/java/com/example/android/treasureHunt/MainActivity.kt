package com.example.android.treasureHunt

import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.os.Handler
import android.view.View
import android.widget.Toast
import androidx.core.content.ContextCompat
import androidx.core.view.isVisible
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import kotlinx.android.synthetic.main.activity_main.*
import org.json.JSONObject

class MainActivity : AppCompatActivity(){
    private val SPLASH_TIME_OUT:Long = 3000
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    val validasi = "http://192.168.43.57/simaptapkl/public/service/akun.php"
    val perbaikan = "http://192.168.43.57/simaptapkl/public/service/konfigurasi.php"
    val konfigurasi = "http://192.168.43.57/simaptapkl/public/service/konfigurasi.php"
    var latitude = ""
    var longitude = ""
    var radius = ""
    var title = ""

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        supportActionBar?.hide()
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
        window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR

        Handler().postDelayed({
            val networkConnection = NetworkConnection(applicationContext)
            networkConnection.observe(this, { isConnected ->
                if (isConnected) {
                    lokasi()
                } else { }
            })
        }, SPLASH_TIME_OUT)
    }

    fun validasi(){
        val request = object : StringRequest(
            Method.POST,validasi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val intent = Intent(this, HuntMainActivity::class.java)
                    intent.putExtra("latitude", latitude)
                    intent.putExtra("longitude", longitude)
                    intent.putExtra("radius", radius)
                    intent.putExtra("judul", title)
                    intent.putExtra("notifikasi", "0")
                    startActivity(intent)
                    Animatoo.animateFade(this)
                    finish()
                }else{
                    val intent = Intent(this, HomePembimbingActivity::class.java)
                    intent.putExtra("latitude", latitude)
                    intent.putExtra("longitude", longitude)
                    intent.putExtra("radius", radius)
                    intent.putExtra("judul", title)
                    startActivity(intent)
                    Animatoo.animateFade(this)
                    finish()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                data.put("pilihan","main")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun lokasi(){
        val request = object : StringRequest(
            Method.POST,konfigurasi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val lat = jsonObject.getString("LATITUDE_KONF")
                val long = jsonObject.getString("LONGITUDE_KONF")
                val rad = jsonObject.getString("RADIUS_KONF")
                val judul = jsonObject.getString("JUDUL_RADIUS")

                latitude = lat
                longitude = long
                radius = rad
                title = judul

                val akun = preferences.getString(ID_AKUN,DEF_ID_AKUN)
                if(!akun.equals("")){
                    validasi()
                }else{
                    val intent = Intent(this, OnboardingActivity::class.java)
                    startActivity(intent)
                    Animatoo.animateFade(this)
                    finish()
                }
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
}
