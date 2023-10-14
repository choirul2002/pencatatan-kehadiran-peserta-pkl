package com.example.android.treasureHunt

import android.app.Activity
import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.graphics.Color
import android.os.Bundle
import android.os.Handler
import android.speech.RecognizerIntent
import android.view.Menu
import android.view.MenuItem
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.SearchView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.LinearLayoutManager
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.activity_asal_peserta.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import org.json.JSONArray
import org.json.JSONObject

class AsalPesertaActivity: AppCompatActivity() {
    val daftarForum = mutableListOf<HashMap<String,String>>()
    val asal = "http://192.168.43.57/simaptapkl/public/service/kampus.php"
    lateinit var forumAsalPeserta: AdapterAsalPeserta
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
        setContentView(R.layout.activity_asal_peserta)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.setTitle("Asal Peserta")
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)

        forumAsalPeserta = AdapterAsalPeserta(daftarForum, this)
        lsForum.layoutManager = LinearLayoutManager(this)
        lsForum.adapter = forumAsalPeserta

        jumlahDataKosong()
        dataKosong()
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
        refreshAsalPeserta.setColorSchemeColors(
            Color.parseColor("#5AA0FF")
        )
        refreshAsalPeserta.setOnRefreshListener {
            Handler().postDelayed(Runnable {
                jumlahDataKosong()
                dataKosong()
                refreshAsalPeserta.isRefreshing = false
            }, 2000)
        }
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
                forumAsalPeserta.filter.filter(newText)
                return true
            }
        })

        return super.onCreateOptionsMenu(menu)
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

    override fun onBackPressed() {
        super.onBackPressed()
        val transitions = Transitions(this)
        transitions.setAnimation(Fade().InLeft())
        finish()
    }

    override fun onSupportNavigateUp(): Boolean {
        finish()
        val transitions = Transitions(this)
        transitions.setAnimation(Fade().InLeft())

        return true
    }

    fun jumlahDataKosong(){
        val request = object : StringRequest(
            Method.POST,asal,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("jumlah")

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
                data.put("pilihan","jumlahViewKampus")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun dataKosong(){
        val request = object : StringRequest(
            Method.POST,asal,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("kode",jsonObject.getString("KD_ASAL"))
                    frm.put("nama",jsonObject.getString("NAMA_ASAL"))
                    frm.put("alamat",jsonObject.getString("ALAMAT_ASAL"))
                    frm.put("tlp",jsonObject.getString("TELP_ASAL"))
                    frm.put("fax",jsonObject.getString("FAX_ASAL"))
                    frm.put("website",jsonObject.getString("WEBSITE_ASAL"))
                    frm.put("kategori",jsonObject.getString("KATEGORI_ASAL"))

                    daftarForum.add(frm)
                }
                forumAsalPeserta.notifyDataSetChanged()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","viewKampus")
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
}