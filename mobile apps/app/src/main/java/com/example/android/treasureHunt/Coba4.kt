package com.example.android.treasureHunt

import android.Manifest
import android.app.*
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.graphics.Color
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.net.Uri
import android.os.*
import android.provider.MediaStore
import android.util.Base64
import android.util.Log
import android.view.View
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.app.NotificationCompat
import androidx.core.app.NotificationManagerCompat
import androidx.core.content.FileProvider
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.livinglifetechway.quickpermissions_kotlin.runWithPermissions
import com.pusher.client.Pusher
import com.pusher.client.PusherOptions
import com.pusher.client.connection.ConnectionEventListener
import com.pusher.client.connection.ConnectionState
import com.pusher.client.connection.ConnectionStateChange
import kotlinx.android.synthetic.main.activity_edit_profil.*
import kotlinx.android.synthetic.main.coba3.*
import okhttp3.*
import org.json.JSONObject
import java.io.ByteArrayOutputStream
import java.io.File
import java.io.IOException
import java.text.SimpleDateFormat
import java.util.*

class Coba4: AppCompatActivity(), View.OnClickListener {
    private lateinit var locationManager: LocationManager
    private lateinit var isDialog: AlertDialog
    private lateinit var pusher: Pusher
    var imStr = ""
    var namaFoto = ""
    private var our_request_code : Int = 123
    private var REQUEST_IMAGE_CAPTURE : Int = 154
    val user = "http://192.168.43.57/simmapkl/public/service/coba.php"
    var fileUri = Uri.parse("")
    var photoURI = Uri.parse("")
    private val REQUEST_CAMERA_PERMISSION = 1
    var namaFile = ""
    private var imageBitmap: Bitmap? = null
    var currentPhotoPath = ""
    lateinit var mediaHelper: MediaHelper
    var CHANNEL_ID = "notifikasi"
    var NOTIFICATION_ID = 0

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.button9->{

            }
            R.id.button10->{
                var file = "Daftar-absensi-MH000001.pdf"
                val url = "http://192.168.43.57/simmapkl/public/service/generate/Daftar-absensi-MH000001.pdf"

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
                downloadmanager.enqueue(request)
            }
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.coba3)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR

        button9.setOnClickListener(this)
        button10.setOnClickListener(this)

    }

}