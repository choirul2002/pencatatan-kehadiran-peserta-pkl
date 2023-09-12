package com.example.android.treasureHunt

import android.Manifest
import android.content.Context
import android.content.SharedPreferences
import android.content.pm.PackageManager
import android.graphics.Bitmap
import android.graphics.Canvas
import android.graphics.Color
import android.os.Bundle
import android.os.Handler
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.Toast
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.LinearLayoutManager
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.getbase.floatingactionbutton.FloatingActionButton
import com.google.android.gms.maps.CameraUpdateFactory
import com.google.android.gms.maps.GoogleMap
import com.google.android.gms.maps.OnMapReadyCallback
import com.google.android.gms.maps.SupportMapFragment
import com.google.android.gms.maps.model.BitmapDescriptorFactory
import com.google.android.gms.maps.model.CircleOptions
import com.google.android.gms.maps.model.LatLng
import com.google.android.gms.maps.model.MarkerOptions
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.activity_hunt_main.*
import kotlinx.android.synthetic.main.fragment_maps_pembimbing.*
import kotlinx.android.synthetic.main.fragment_maps_pembimbing.view.*
import org.json.JSONArray
import java.util.*
import kotlin.collections.ArrayList

class FragmentMapsPembimbing: Fragment(), OnMapReadyCallback {
    lateinit var thisParent:HomePembimbingActivity
    lateinit var v: View
    var gMap : GoogleMap? = null
    lateinit var mapFragment : SupportMapFragment
    val lokasi = "http://192.168.43.57/simaptapkl/public/service/logpos.php"
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    var clear = "0"
    var kondisi = ""
    var dataAsal = ""
    var dataTim = ""
    var dataPerson = ""

    val daftarForum = mutableListOf<HashMap<String,String>>()
    val daftarForum2 = mutableListOf<HashMap<String,String>>()
    val daftarForum3 = mutableListOf<HashMap<String,String>>()
    val asal = "http://192.168.43.57/simaptapkl/public/service/kampus.php"
    val anggota = "http://192.168.43.57/simaptapkl/public/service/timPeserta.php"
    lateinit var forumMapsAsal: AdapterMapsAsal
    lateinit var forumMapsTim: AdapterMapsTim
    lateinit var forumMapsPerson: AdapterMapsPerson

    override fun onMapReady(p0: GoogleMap?) {
        gMap = p0
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        thisParent = activity as HomePembimbingActivity

        v = inflater.inflate(R.layout.fragment_maps_pembimbing,container,false)
        forumMapsAsal = AdapterMapsAsal(daftarForum, thisParent, this)
        forumMapsTim = AdapterMapsTim(daftarForum2, thisParent, this)
        forumMapsPerson = AdapterMapsPerson(daftarForum3, thisParent, this)
        v.lsAsal.layoutManager = LinearLayoutManager(thisParent)
        v.lsTim.layoutManager = LinearLayoutManager(thisParent)
        v.lsPerson.layoutManager = LinearLayoutManager(thisParent)
        v.lsAsal.adapter = forumMapsAsal
        v.lsTim.adapter = forumMapsTim
        v.lsPerson.adapter = forumMapsPerson

        dataKosong()
        dataTimPeserta()
        dataPeserta()

        val data = arguments
        kondisi = data?.get("kondisi").toString()

        v.cardView13.setOnClickListener {
            flPerson.visibility = View.VISIBLE
            thisParent.hideBottomNavigationItemBeranda(R.id.btnBeranda)
            thisParent.hideBottomNavigationItemLokasi(R.id.btnLokasi)
            thisParent.hideBottomNavigationItemPengaturan(R.id.btnSistem)
            v.cardView13.visibility = View.GONE
            v.btnRfsh.visibility = View.GONE
        }

        v.profile_image10.setOnClickListener {
            flPerson.visibility = View.GONE
            thisParent.showBottomNavigationItemBeranda(R.id.btnBeranda)
            thisParent.showBottomNavigationItemLokasi(R.id.btnLokasi)
            thisParent.showBottomNavigationItemPengaturan(R.id.btnSistem)
            v.cardView13.visibility = View.VISIBLE
            v.btnRfsh.visibility = View.VISIBLE
        }

        v.profile_image100.setOnClickListener {
            flAsal.visibility = View.GONE
            flPerson.visibility = View.VISIBLE
        }

        v.profile_image1000.setOnClickListener {
            flTim.visibility = View.GONE
            flPerson.visibility = View.VISIBLE
        }

        v.cardView18.setOnClickListener {
            flPerson.visibility = View.GONE
            flAsal.visibility = View.VISIBLE
        }

        v.cardView20.setOnClickListener {
            flPerson.visibility = View.GONE
            flTim.visibility = View.VISIBLE
        }

        v.btnRfsh.setOnClickListener {
            kondisi = "semua"
            thisParent.timer.cancel()
            cekBerkala()
        }

        mapFragment = childFragmentManager.findFragmentById(R.id.fragmen) as SupportMapFragment
        mapFragment.getMapAsync(this)

        thisParent.cektimer = "1"
        cekBerkala()

        return v
    }

    fun dataKosong(){
        val request = object : StringRequest(
            Method.POST,asal,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                if (jsonArray.length() == 0) {
                    lsAsal.visibility = View.GONE
                    imageView27.visibility = View.VISIBLE
                }else{
                    for (x in 0..(jsonArray.length()-1)){
                        val jsonObject = jsonArray.getJSONObject(x)
                        var  frm = HashMap<String,String>()
                        frm.put("kode",jsonObject.getString("KD_ASAL"))
                        frm.put("nama",jsonObject.getString("NAMA_ASAL"))

                        daftarForum.add(frm)
                    }
                    forumMapsAsal.notifyDataSetChanged()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(thisParent,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","viewKampus")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(thisParent)
        queue.add(request)
    }

    fun dataTimPeserta(){
        val request = object : StringRequest(
            Method.POST,anggota,
            Response.Listener { response ->
                daftarForum2.clear()
                val jsonArray = JSONArray(response)
                if (jsonArray.length() == 0) {
                    lsTim.visibility = View.GONE
                    imageView26.visibility = View.VISIBLE
                }else{
                    for (x in 0..(jsonArray.length()-1)){
                        val jsonObject = jsonArray.getJSONObject(x)
                        var  frm = HashMap<String,String>()
                        frm.put("kode",jsonObject.getString("KD_TIM"))
                        frm.put("tim",jsonObject.getString("NAMA_TIM"))

                        daftarForum2.add(frm)
                    }
                    forumMapsTim.notifyDataSetChanged()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(thisParent,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","dataTimPesertaPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(thisParent)
        queue.add(request)
    }

    fun dataPeserta(){
        val request = object : StringRequest(
            Method.POST,anggota,
            Response.Listener { response ->
                daftarForum3.clear()
                val jsonArray = JSONArray(response)
                if (jsonArray.length() == 0) {
                    lsPerson.visibility = View.GONE
                    imageView28.visibility = View.VISIBLE
                }else{
                    for (x in 0..(jsonArray.length()-1)){
                        val jsonObject = jsonArray.getJSONObject(x)
                        var  frm = HashMap<String,String>()
                        frm.put("kode",jsonObject.getString("KD_PST"))
                        frm.put("nama",jsonObject.getString("NAMA_PST"))

                        daftarForum3.add(frm)
                    }
                    forumMapsPerson.notifyDataSetChanged()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(thisParent,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","dataTimPesertaPembimbingMaps")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(thisParent)
        queue.add(request)
    }

    fun cekBerkala() {
        thisParent.timer = Timer()

        val task = object : TimerTask() {
            override fun run() {
                activity?.runOnUiThread {
                    if(kondisi.equals("semua")){
                        if(thisParent.cekKoneksi.equals("0")){
                            activity?.runOnUiThread {
                                gMap?.clear()
                            }
                        }else{
                            if(clear.equals("0")){
                                clear = "1"
                                dataLokasi()

                                val data = arguments
                                val lat = data?.get("latitude").toString()
                                val long = data?.get("longitude").toString()
                                val geo = LatLng(lat.toDouble(),long.toDouble())

                                activity?.runOnUiThread {
                                    gMap?.moveCamera(CameraUpdateFactory.newLatLngZoom(geo, 17f))
                                }
                            }else{
                                activity?.runOnUiThread {
                                    gMap?.clear()
                                }

                                activity?.runOnUiThread {
                                    if(!thisParent.zoom.equals("0")){
                                        val data = arguments
                                        val lat = data?.get("latitude").toString()
                                        val long = data?.get("longitude").toString()
                                        val geo = LatLng(lat.toDouble(),long.toDouble())

                                        gMap?.moveCamera(CameraUpdateFactory.newLatLngZoom(geo, 17f))

                                        thisParent.zoom = "0"
                                    }
                                }

                                dataLokasi()
                            }
                        }
                    }else if(kondisi.equals("person")){
                        if(thisParent.cekKoneksi.equals("0")){
                            activity?.runOnUiThread {
                                gMap?.clear()
                            }
                        }else{
                            if(clear.equals("0")){
                                clear = "1"
                                dataLokasiPerson(dataPerson)

                                val data = arguments
                                val lat = data?.get("latitude").toString()
                                val long = data?.get("longitude").toString()
                                val geo = LatLng(lat.toDouble(),long.toDouble())

                                activity?.runOnUiThread {
                                    gMap?.moveCamera(CameraUpdateFactory.newLatLngZoom(geo, 17f))
                                }
                            }else{
                                activity?.runOnUiThread {
                                    gMap?.clear()
                                }

                                activity?.runOnUiThread {
                                    if(!thisParent.zoom.equals("0")){
                                        val data = arguments
                                        val lat = data?.get("latitude").toString()
                                        val long = data?.get("longitude").toString()
                                        val geo = LatLng(lat.toDouble(),long.toDouble())

                                        gMap?.moveCamera(CameraUpdateFactory.newLatLngZoom(geo, 17f))

                                        thisParent.zoom = "0"
                                    }
                                }

                                dataLokasiPerson(dataPerson)
                            }
                        }
                    }else if(kondisi.equals("tim")){
                        if(thisParent.cekKoneksi.equals("0")){
                            activity?.runOnUiThread {
                                gMap?.clear()
                            }
                        }else{
                            if(clear.equals("0")){
                                clear = "1"
                                dataLokasiTim(dataTim)

                                val data = arguments
                                val lat = data?.get("latitude").toString()
                                val long = data?.get("longitude").toString()
                                val geo = LatLng(lat.toDouble(),long.toDouble())

                                activity?.runOnUiThread {
                                    gMap?.moveCamera(CameraUpdateFactory.newLatLngZoom(geo, 17f))
                                }
                            }else{
                                activity?.runOnUiThread {
                                    gMap?.clear()
                                }

                                activity?.runOnUiThread {
                                    if(!thisParent.zoom.equals("0")){
                                        val data = arguments
                                        val lat = data?.get("latitude").toString()
                                        val long = data?.get("longitude").toString()
                                        val geo = LatLng(lat.toDouble(),long.toDouble())

                                        gMap?.moveCamera(CameraUpdateFactory.newLatLngZoom(geo, 17f))

                                        thisParent.zoom = "0"
                                    }
                                }

                                dataLokasiTim(dataTim)
                            }
                        }
                    }else if(kondisi.equals("asal")){
                        if(thisParent.cekKoneksi.equals("0")){
                            activity?.runOnUiThread {
                                gMap?.clear()
                            }
                        }else{
                            if(clear.equals("0")){
                                clear = "1"
                                dataLokasiAsal(dataAsal)

                                val data = arguments
                                val lat = data?.get("latitude").toString()
                                val long = data?.get("longitude").toString()
                                val geo = LatLng(lat.toDouble(),long.toDouble())

                                activity?.runOnUiThread {
                                    gMap?.moveCamera(CameraUpdateFactory.newLatLngZoom(geo, 17f))
                                }
                            }else{
                                activity?.runOnUiThread {
                                    gMap?.clear()
                                }

                                activity?.runOnUiThread {
                                    if(!thisParent.zoom.equals("0")){
                                        val data = arguments
                                        val lat = data?.get("latitude").toString()
                                        val long = data?.get("longitude").toString()
                                        val geo = LatLng(lat.toDouble(),long.toDouble())

                                        gMap?.moveCamera(CameraUpdateFactory.newLatLngZoom(geo, 17f))

                                        thisParent.zoom = "0"
                                    }
                                }

                                dataLokasiAsal(dataAsal)
                            }
                        }
                    }
                }
            }
        }

        thisParent.timer.schedule(task, 0L, 3L * 1000L)
    }

    private fun addMarker(latLng: LatLng, judul:String){
        val markerOptions = MarkerOptions().position(latLng).title(judul).icon(BitmapDescriptorFactory.fromBitmap(getMarkerBitmapFromView2()))
        gMap!!.addMarker(markerOptions)
    }

    private fun addCircle(latLng: LatLng, radius: Float){
        val circleOptions = CircleOptions()
        circleOptions.center(latLng)
        circleOptions.radius(radius.toDouble())
        circleOptions.strokeColor(Color.rgb(83, 127, 231))
        circleOptions.fillColor(Color.argb(40,83,127,231))
        circleOptions.strokeWidth(4f)
        gMap!!.addCircle(circleOptions)
    }

    fun dataLokasi(){
        val request = object : StringRequest(
            Method.POST,lokasi,
            Response.Listener { response ->
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)

                    val latitude = jsonObject.getString("LATITUDE_LOK")
                    val longitude = jsonObject.getString("LONGITUDE_LOK")
                    val nama = jsonObject.getString("NAMA_PST")
                    val url = jsonObject.getString("url")
                    val koordinat = LatLng(latitude.toDouble(),longitude.toDouble())

                    gMap!!.addMarker(MarkerOptions().position(koordinat).title(nama.toString()).icon(BitmapDescriptorFactory.fromBitmap(getMarkerBitmapFromView(url.toString()))))
                }

                val data = arguments
                val lat = data?.get("latitude").toString()
                val long = data?.get("longitude").toString()
                val radius = data?.get("radius").toString()
                val title = data?.get("title").toString()
                val geo = LatLng(lat.toDouble(),long.toDouble())

                addMarker(geo, title)
                addCircle(geo, radius.toFloat())
                println("Mengecek setiap 3 detik")
            },
            Response.ErrorListener { error ->
                Toast.makeText(thisParent,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","lokasiPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(thisParent)
        queue.add(request)
    }

    fun dataLokasiPerson(kode:String){
        val request = object : StringRequest(
            Method.POST,lokasi,
            Response.Listener { response ->
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)

                    val latitude = jsonObject.getString("LATITUDE_LOK")
                    val longitude = jsonObject.getString("LONGITUDE_LOK")
                    val nama = jsonObject.getString("NAMA_PST")
                    val url = jsonObject.getString("url")
                    val koordinat = LatLng(latitude.toDouble(),longitude.toDouble())

                    gMap!!.addMarker(MarkerOptions().position(koordinat).title(nama.toString()).icon(BitmapDescriptorFactory.fromBitmap(getMarkerBitmapFromView(url.toString()))))
                }

                val data = arguments
                val lat = data?.get("latitude").toString()
                val long = data?.get("longitude").toString()
                val radius = data?.get("radius").toString()
                val title = data?.get("title").toString()
                val geo = LatLng(lat.toDouble(),long.toDouble())

                addMarker(geo, title)
                addCircle(geo, radius.toFloat())
                println("Mengecek setiap 3 detik")
            },
            Response.ErrorListener { error ->
                Toast.makeText(thisParent,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","lokasiPembimbingPerson")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("person",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(thisParent)
        queue.add(request)
    }

    fun dataLokasiAsal(kode:String){
        val request = object : StringRequest(
            Method.POST,lokasi,
            Response.Listener { response ->
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)

                    val latitude = jsonObject.getString("LATITUDE_LOK")
                    val longitude = jsonObject.getString("LONGITUDE_LOK")
                    val nama = jsonObject.getString("NAMA_PST")
                    val url = jsonObject.getString("url")
                    val koordinat = LatLng(latitude.toDouble(),longitude.toDouble())

                    gMap!!.addMarker(MarkerOptions().position(koordinat).title(nama.toString()).icon(BitmapDescriptorFactory.fromBitmap(getMarkerBitmapFromView(url.toString()))))
                }

                val data = arguments
                val lat = data?.get("latitude").toString()
                val long = data?.get("longitude").toString()
                val radius = data?.get("radius").toString()
                val title = data?.get("title").toString()
                val geo = LatLng(lat.toDouble(),long.toDouble())

                addMarker(geo, title)
                addCircle(geo, radius.toFloat())
                println("Mengecek setiap 3 detik")
            },
            Response.ErrorListener { error ->
                Toast.makeText(thisParent,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","lokasiPembimbingAsal")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("asal",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(thisParent)
        queue.add(request)
    }

    fun dataLokasiTim(kode:String){
        val request = object : StringRequest(
            Method.POST,lokasi,
            Response.Listener { response ->
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)

                    val latitude = jsonObject.getString("LATITUDE_LOK")
                    val longitude = jsonObject.getString("LONGITUDE_LOK")
                    val nama = jsonObject.getString("NAMA_PST")
                    val url = jsonObject.getString("url")
                    val koordinat = LatLng(latitude.toDouble(),longitude.toDouble())

                    gMap!!.addMarker(MarkerOptions().position(koordinat).title(nama.toString()).icon(BitmapDescriptorFactory.fromBitmap(getMarkerBitmapFromView(url.toString()))))
                }

                val data = arguments
                val lat = data?.get("latitude").toString()
                val long = data?.get("longitude").toString()
                val radius = data?.get("radius").toString()
                val title = data?.get("title").toString()
                val geo = LatLng(lat.toDouble(),long.toDouble())

                addMarker(geo, title)
                addCircle(geo, radius.toFloat())
                println("Mengecek setiap 3 detik")
            },
            Response.ErrorListener { error ->
                Toast.makeText(thisParent,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","lokasiPembimbingTim")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("tim",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(thisParent)
        queue.add(request)
    }

    private fun getMarkerBitmapFromView(drawableRes: Int): Bitmap {
        val view = layoutInflater.inflate(R.layout.marker_layout, null)
        val profileImageView = view.findViewById<ImageView>(R.id.profileImageView)
        profileImageView.setImageResource(drawableRes)

        val displayMetrics = resources.displayMetrics
        view.measure(displayMetrics.widthPixels, displayMetrics.heightPixels)
        view.layout(0, 0, displayMetrics.widthPixels, displayMetrics.heightPixels)

        val bitmap = Bitmap.createBitmap(view.measuredWidth, view.measuredHeight, Bitmap.Config.ARGB_8888)
        val canvas = Canvas(bitmap)
        view.draw(canvas)

        return bitmap
    }

    private fun getMarkerBitmapFromView(foto: String): Bitmap {
        val view = layoutInflater.inflate(R.layout.marker_layout, null)
        val profileImageView = view.findViewById<ImageView>(R.id.profileImageView)
        Picasso.get().load(foto).into(profileImageView)

        val displayMetrics = resources.displayMetrics
        view.measure(displayMetrics.widthPixels, displayMetrics.heightPixels)
        view.layout(0, 0, displayMetrics.widthPixels, displayMetrics.heightPixels)

        val bitmap = Bitmap.createBitmap(view.measuredWidth, view.measuredHeight, Bitmap.Config.ARGB_8888)
        val canvas = Canvas(bitmap)
        view.draw(canvas)

        return bitmap
    }

    private fun getMarkerBitmapFromView2(): Bitmap {
        val view = layoutInflater.inflate(R.layout.marker_layout2, null)

        val displayMetrics = resources.displayMetrics
        view.measure(displayMetrics.widthPixels, displayMetrics.heightPixels)
        view.layout(0, 0, displayMetrics.widthPixels, displayMetrics.heightPixels)

        val bitmap = Bitmap.createBitmap(view.measuredWidth, view.measuredHeight, Bitmap.Config.ARGB_8888)
        val canvas = Canvas(bitmap)
        view.draw(canvas)

        return bitmap
    }

    override fun onDestroy() {
        super.onDestroy()
        thisParent.timer.cancel()
    }
}