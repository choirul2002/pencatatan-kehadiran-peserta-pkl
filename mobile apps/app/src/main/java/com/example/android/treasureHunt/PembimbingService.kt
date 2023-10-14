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
import android.widget.Toast
import androidx.core.app.ActivityCompat
import androidx.core.app.NotificationCompat
import com.google.android.gms.location.Geofence
import com.google.android.gms.location.GeofencingClient
import com.google.android.gms.location.GeofencingRequest
import com.google.android.gms.location.LocationServices
import com.pusher.client.Pusher
import com.pusher.client.PusherOptions
import com.pusher.client.connection.ConnectionEventListener
import com.pusher.client.connection.ConnectionState
import com.pusher.client.connection.ConnectionStateChange
import org.json.JSONObject

class PembimbingService : Service() {

    companion object {
        const val CHANNEL_ID = "MyServiceChannel"
        const val NOTIFICATION_ID = 111111
    }

    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    lateinit var preferences: SharedPreferences

    override fun onBind(intent: Intent?): IBinder? {
        return null
    }

    override fun onCreate() {
        super.onCreate()

        createChannel(this )
    }

    override fun onTaskRemoved(rootIntent: Intent?) {
        super.onTaskRemoved(rootIntent)

        Handler(Looper.getMainLooper()).post {
            val service = Intent(this, PembimbingService::class.java)
            stopService(service)
        }
    }

    @SuppressLint("MissingPermission")
    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        super.onStartCommand(intent, flags, startId)

        createNotificationChannel()
        val notification = createNotification()
        startForeground(NOTIFICATION_ID, notification)

        createPusher()
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)

        return START_STICKY
    }

    private fun createPusher(){
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
                    } else if (change.currentState == ConnectionState.CONNECTED) {
                        Log.i(TAG, "Connection established")
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
            channel.bind("peserta") { event ->

                val isi = event.data

                val jsonObject = JSONObject(isi)
                val nama = jsonObject.getString("nama")
                val pembimbing = jsonObject.getString("pembimbing")

                if(pembimbing.equals(preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())){
                    sendNotificationRadius(this, nama)
                }
            }

            channel.bind("perbarui") { event ->

                val isi = event.data

                val jsonObject = JSONObject(isi)
                val nama = jsonObject.getString("nama")
                val kode = jsonObject.getString("kode")
                val pembimbing = jsonObject.getString("pembimbing")

                if(pembimbing.equals(preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())){
                    sendNotificationPerbarui(this, nama, kode)
                }
            }

            channel.bind("destroy") { event ->
                val isi = event.data

                val jsonObject = JSONObject(isi)
                val nama = jsonObject.getString("nama")
                val kode = jsonObject.getString("kode")
                val pembimbing = jsonObject.getString("pembimbing")

                if(pembimbing.equals(preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())){
                    sendNotificationService(this, nama, kode)
                }
            }

            channel.bind("pesertaIzin") { event ->

                val isi = event.data

                val jsonObject = JSONObject(isi)
                val nama = jsonObject.getString("nama")
                val surat = jsonObject.getString("surat")
                val pembimbing = jsonObject.getString("pembimbing")

                if(pembimbing.equals(preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())){
                    sendNotificationIzinPeserta(this, nama, surat)
                }
            }
        } catch (e: NullPointerException) {
                Log.e("TAG", "NullPointerException occurred: ${e.message}")
        }
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
            .setContentText("Pembimbing Service is running ...")
            .setPriority(NotificationCompat.PRIORITY_DEFAULT)
        return builder.build()
    }
}