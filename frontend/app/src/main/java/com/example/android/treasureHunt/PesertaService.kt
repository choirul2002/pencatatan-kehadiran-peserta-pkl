package com.example.android.treasureHunt

import android.Manifest
import android.annotation.SuppressLint
import android.app.*
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.content.pm.PackageManager
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.os.*
import android.util.Log
import android.widget.TextView
import android.widget.Toast
import androidx.core.app.ActivityCompat
import androidx.core.app.NotificationCompat
import androidx.core.content.ContextCompat
import androidx.lifecycle.Lifecycle
import androidx.lifecycle.LifecycleOwner
import androidx.lifecycle.LifecycleRegistry
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import com.google.android.gms.location.Geofence
import com.google.android.gms.location.GeofencingClient
import com.google.android.gms.location.GeofencingRequest
import com.google.android.gms.location.LocationServices
import com.loopj.android.http.AsyncHttpClient
import com.loopj.android.http.AsyncHttpResponseHandler
import com.loopj.android.http.RequestParams
import com.pusher.client.Pusher
import com.pusher.client.PusherOptions
import com.pusher.client.connection.ConnectionEventListener
import com.pusher.client.connection.ConnectionState
import com.pusher.client.connection.ConnectionStateChange
import cz.msebera.android.httpclient.Header
import org.json.JSONObject
import java.util.*

class PesertaService : Service(), LocationListener {

    companion object {
        const val CHANNEL_ID = "MyServiceChannel"
        const val NOTIFICATION_ID = 111111
    }

    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    lateinit var preferences: SharedPreferences
    private lateinit var geofencingClient: GeofencingClient
    private lateinit var locationManager: LocationManager
    val lokasi = "http://192.168.43.57/simaptapkl/public/service/logpos.php"
    val logservice = "http://192.168.43.57/simaptapkl/public/service/logservice.php"
    private lateinit var timer: Timer
    private lateinit var kuy: Timer

    private val geofencePendingIntent: PendingIntent by lazy {
        val intent = Intent(this, GeofenceBroadcastReceiver::class.java)
        intent.action = HuntMainActivity.ACTION_GEOFENCE_EVENT
        PendingIntent.getBroadcast(this, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)
    }

    override fun onLocationChanged(location: Location) {
        var latitude = location.latitude.toString()
        var longitude = location.longitude.toString()

        println(latitude+" / "+longitude)
    }

    override fun onStatusChanged(provider: String, status: Int, extras: Bundle) {}

    override fun onProviderEnabled(provider: String) {
        cekBerkala(this)
    }

    override fun onProviderDisabled(provider: String) {
        hapusLokasi(this)
        timer.cancel()
    }

    override fun onBind(intent: Intent?): IBinder? {
        return null
    }

    override fun onCreate() {
        super.onCreate()

        locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager
        geofencingClient = LocationServices.getGeofencingClient(this)
        createChannel(this )
        cekBerkala(this)
    }

    @SuppressLint("MissingPermission")
    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        super.onStartCommand(intent, flags, startId)

        if (ActivityCompat.checkSelfPermission(
                this,
                Manifest.permission.ACCESS_FINE_LOCATION
            ) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(
                this,
                Manifest.permission.ACCESS_COARSE_LOCATION
            ) != PackageManager.PERMISSION_GRANTED
        ) {
            // Jika permission belum diberikan, tampilkan pesan
            return START_NOT_STICKY
        }

        locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0f, this)

        createNotificationChannel()
        val notification = createNotification()
        startForeground(NOTIFICATION_ID, notification)

        createPusher(this)
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)

        kuy = Timer()

        val task = object : TimerTask() {
            override fun run() {
                Handler(Looper.getMainLooper()).post {
                    println("service berjalan")
                }
            }
        }

        kuy.schedule(task, 0L, 3L * 1000L)

        var paket : Bundle? = intent!!.extras
        addGeofenceClue(paket?.getString("latitude").toString(), paket?.getString("longitude").toString(), paket?.getString("radius").toString())

        return START_STICKY
    }

    private fun createPusher(context: Context){
        val options = PusherOptions()

        try {
            options.setCluster("ap1");

            val pusher = Pusher("c3100747ee53df61dca0", options)

            pusher.connect(object : ConnectionEventListener {
                private val TAG = "Pusher"

                override fun onConnectionStateChange(change: ConnectionStateChange) {
                    Log.i(TAG, "State changed from ${change.previousState} to ${change.currentState}")

                    if (change.currentState == ConnectionState.DISCONNECTED) {
                        Log.i(TAG, "Connection lost")

                        //posisi delete
                    } else if (change.currentState == ConnectionState.CONNECTED) {
                        Log.i(TAG, "Connection established")
                        //posisi tambah
                    }
                }

                override fun onError(code: String?, message: String?, e: Exception?) {
                    if (code != null) { } else { }
                }
            }, ConnectionState.ALL)
            val cekChannel = pusher.getChannel("my-channel")

            if (cekChannel != null) {
                pusher.unsubscribe("my-channel")
            }

            val channel = pusher.subscribe("my-channel")
            channel.bind("pembimbing") { event ->

                val isi = event.data

                val jsonObject = JSONObject(isi)
                val nama = jsonObject.getString("nama")
                val kodePeserta = jsonObject.getString("kodePeserta")
                val surat = jsonObject.getString("surat")
                val statusSurat = jsonObject.getString("statusSurat")
                val alasan = jsonObject.getString("alasan")

                if(kodePeserta.equals(preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())){
                    sendNotificationIzin(this, nama, surat, statusSurat, alasan)
                }
            }

            channel.bind("berhasilperbarui") { event ->

                val isi = event.data

                val jsonObject = JSONObject(isi)
                val pembimbing = jsonObject.getString("pembimbing")
                val kodePeserta = jsonObject.getString("akun")

                if(kodePeserta.equals(preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())){
                    sendNotificationBerhasilPerbarui(this, pembimbing)
                }
            }

            channel.bind("notifikasiPeserta") { event ->

                val isi = event.data

                val jsonObject = JSONObject(isi)
                val peserta = jsonObject.getString("peserta")

                if(peserta.equals(preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())){
                    sendNotificationPresensi(this)
                }
            }

            channel.bind("service") { event ->
                sendNotificationService(this)
            }
        } catch (e: NullPointerException) {
                Log.e("TAG", "NullPointerException occurred: ${e.message}")
        }
    }

    private fun addGeofenceClue(lat:String, long:String, rad:String){
        val geofenceId = "golden_gate_bridge"
        val geofence = Geofence.Builder()
            .setRequestId(geofenceId)
            .setCircularRegion(lat.toDouble(),
                long.toDouble(),
                rad.toFloat()
            )
            .setExpirationDuration(GeofencingConstants.GEOFENCE_EXPIRATION_IN_MILLISECONDS)
            .setTransitionTypes(Geofence.GEOFENCE_TRANSITION_EXIT or Geofence.GEOFENCE_TRANSITION_ENTER)
            .build()

        val geofencingRequest = GeofencingRequest.Builder()
            .setInitialTrigger(GeofencingRequest.INITIAL_TRIGGER_EXIT or Geofence.GEOFENCE_TRANSITION_ENTER)
            .addGeofence(geofence)
            .build()

        geofencingClient.removeGeofences(geofencePendingIntent)?.run {
            addOnCompleteListener {
                geofencingClient.addGeofences(geofencingRequest, geofencePendingIntent)?.run {
                    addOnSuccessListener {
                        Log.e("Add Geofence", geofence.requestId)
                    }
                    addOnFailureListener {
                        Toast.makeText(this@PesertaService, R.string.geofences_not_added,
                            Toast.LENGTH_SHORT).show()
                        if ((it.message != null)) {

                        }
                    }
                }
            }
        }
    }

    override fun onDestroy() {
        super.onDestroy()

        locationManager.removeUpdates(this)
        timer.cancel()
        kuy.cancel()
    }

    override fun onTaskRemoved(rootIntent: Intent?) {
        super.onTaskRemoved(rootIntent)

        Handler(Looper.getMainLooper()).post {
            hapusLokasiService(this)
            val service = Intent(this, PesertaService::class.java)
            stopService(service)
        }
    }

    private fun kirimNotifikasi(kode:String){
        val client = AsyncHttpClient()
        val params = RequestParams()
        params.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
        params.put("pilihan", "destroy")
        params.put("kode", kode)
        params.put("event", "destroy")

        client.post(
            "http://192.168.43.57/simaptapkl/public/service/kirimNotifikasi.php",
            params,
            object : AsyncHttpResponseHandler() {
                override fun onSuccess(
                    statusCode: Int,
                    headers: Array<Header?>?,
                    responseBody: ByteArray?
                ) {

                }

                override fun onFailure(
                    statusCode: Int,
                    headers: Array<Header?>?,
                    responseBody: ByteArray?,
                    error: Throwable?
                ) {
                }
            })
    }

    private fun createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val name = "My Service Channel"
            val descriptionText = "Channel for My Service"
            val importance = NotificationManager.IMPORTANCE_DEFAULT
            val channel = NotificationChannel(CHANNEL_ID, name, importance).apply {
                description = descriptionText
            }
            getSystemService(NotificationManager::class.java)?.createNotificationChannel(channel)
        }
    }

    private fun createNotification(): Notification {
        val builder = NotificationCompat.Builder(this, CHANNEL_ID)
            .setSmallIcon(R.drawable.kerangka)
            .setContentTitle("My Service")
            .setContentText("Peserta Service is running ...")
            .setPriority(NotificationCompat.PRIORITY_DEFAULT)
        return builder.build()
    }

    fun cekBerkala(context: Context) {
        timer = Timer()

        val task = object : TimerTask() {
            override fun run() {
                val ceklocationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager

                if (ContextCompat.checkSelfPermission(
                        context,
                        Manifest.permission.ACCESS_FINE_LOCATION
                    ) != PackageManager.PERMISSION_GRANTED
                ) {
                    return
                }

                val lastKnownLocation = ceklocationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER)

                lastKnownLocation?.let {
                    val latitude = it.latitude
                    val longitude = it.longitude
                    updateLokasi(context, latitude.toString(), longitude.toString())
                }
            }
        }

        timer.schedule(task, 0L, 3L * 1000L)
    }

    private fun updateLokasi(context:Context, lat:String, long:String){
        val request = object : StringRequest(
            Method.POST,lokasi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    println("Berhasil update lokasi")
                }
            },
            Response.ErrorListener { error ->

            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","lokasi")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("latitude",lat)
                data.put("longitude",long)

                return data
            }
        }
        val queue = Volley.newRequestQueue(context)
        queue.add(request)
    }

    private fun tambahLogService(context:Context){
        val request = object : StringRequest(
            Method.POST,logservice,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("0")){

                }else{
                    println("Berhasil menambah log service")
                    kirimNotifikasi(respon)
                }
            },
            Response.ErrorListener { error ->

            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","service")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(context)
        queue.add(request)
    }

    private fun hapusLokasiService(context:Context){
        val request = object : StringRequest(
            Method.POST,lokasi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    tambahLogService(context)
                }
            },
            Response.ErrorListener { error ->

            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","hapusceklokasi")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(context)
        queue.add(request)
    }

    private fun hapusLokasi(context:Context){
        val request = object : StringRequest(
            Method.POST,lokasi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    println("Berhasil hapus lokasi")
                }
            },
            Response.ErrorListener { error ->

            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","hapusceklokasi")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(context)
        queue.add(request)
    }
}