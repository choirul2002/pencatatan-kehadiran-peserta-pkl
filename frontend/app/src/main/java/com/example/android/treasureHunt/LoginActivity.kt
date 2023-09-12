package com.example.android.treasureHunt

import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.os.Bundle
import android.os.Handler
import android.view.View
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import kotlinx.android.synthetic.main.activity_login.*
import org.json.JSONObject

class LoginActivity:AppCompatActivity(), View.OnClickListener {
    val validasi = "http://192.168.43.57/simaptapkl/public/service/login.php"
    val konfigurasi = "http://192.168.43.57/simaptapkl/public/service/konfigurasi.php"
    lateinit var preferences: SharedPreferences
    var latitude = ""
    var longitude = ""
    var radius = ""
    var title = ""
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    var cekKoneksi = "1"
    private lateinit var isDialog: AlertDialog

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.signIn->{
                if(email.text.toString().equals("") or password.text.toString().equals("")){
                    Toast.makeText(this, "Masukkan data user", Toast.LENGTH_LONG).show()
                }else{
                    lokasi()
                    validasi(this)
                }
            }
        }
    }
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        supportActionBar?.hide()
        window.statusBarColor = ContextCompat.getColor(this, R.color.login)

        signIn.setOnClickListener(this)

        val networkConnection = NetworkConnection(applicationContext)
        networkConnection.observe(this, { isConnected ->
            if (isConnected) {
                if(cekKoneksi.equals("0")){
                    isDismissInternet()
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

    fun validasi(context: Context){
        val request = object : StringRequest(Method.POST,validasi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                            val id = jsonObject.getString("id")
                            val prefEditor = preferences.edit()
                            prefEditor.putString(ID_AKUN,id)
                            prefEditor.commit()

                            val intent = Intent(context, HuntMainActivity::class.java)
                            intent.putExtra("latitude", latitude)
                            intent.putExtra("longitude", longitude)
                            intent.putExtra("radius", radius)
                            intent.putExtra("judul", title)
                            intent.putExtra("notifikasi", "0")
                            startActivity(intent)
                            Animatoo.animateFade(context)
                            finish()
                        }
                    },3000)
                }else if(respon.equals("2")){
                    val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                            val id = jsonObject.getString("id")
                            val prefEditor = preferences.edit()
                            prefEditor.putString(ID_AKUN,id)
                            prefEditor.commit()

                            val intent = Intent(context, HomePembimbingActivity::class.java)
                            intent.putExtra("latitude", latitude)
                            intent.putExtra("longitude", longitude)
                            intent.putExtra("radius", radius)
                            intent.putExtra("judul", title)
                            startActivity(intent)
                            Animatoo.animateFade(context)
                            finish()
                        }
                    },3000)
                }else if(respon.equals("3")){
                    val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            Toast.makeText(context,"User akun anda tidak memiliki hak akses", Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }else if(respon.equals("4")){
                    val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            Toast.makeText(context,"User akun belum memiliki tim", Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }else if(respon.equals("0")){
                    val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            Toast.makeText(context,"User akun salah", Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                data.put("email",email.text.toString())
                data.put("password",password.text.toString())

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