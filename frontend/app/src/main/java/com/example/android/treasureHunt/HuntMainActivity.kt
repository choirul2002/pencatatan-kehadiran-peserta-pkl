/*
 * Copyright (C) 2019 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package com.example.android.treasureHunt

import android.content.pm.PackageManager
import android.Manifest
import android.animation.Animator
import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.annotation.TargetApi
import android.app.*
import android.content.*
import android.graphics.Color
import android.location.Geocoder
import android.media.RingtoneManager
import android.net.Uri
import android.os.Build
import android.os.Bundle
import android.os.Handler
import android.provider.Settings
import android.util.Log
import android.view.View.OnClickListener
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.app.NotificationCompat
import androidx.core.app.NotificationManagerCompat
import androidx.core.content.ContextCompat
import androidx.databinding.DataBindingUtil
import androidx.fragment.app.FragmentTransaction
import androidx.lifecycle.SavedStateViewModelFactory
import androidx.lifecycle.ViewModelProviders
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import com.example.android.treasureHunt.databinding.*
import com.google.android.gms.common.api.ResolvableApiException
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.snackbar.Snackbar
import com.google.android.material.textfield.TextInputEditText
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.os.Looper
import android.view.*
import android.widget.TextView
import com.google.android.gms.location.*
import com.loopj.android.http.AsyncHttpClient
import com.loopj.android.http.AsyncHttpResponseHandler
import com.loopj.android.http.RequestParams
import com.squareup.picasso.Picasso
import cz.msebera.android.httpclient.Header
import kotlinx.android.synthetic.main.activity_hunt_main.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import mumayank.com.airlocationlibrary.AirLocation
import org.json.JSONObject
import java.util.*
import kotlin.collections.HashMap

/**
 * The Treasure Hunt app is a single-player game based on geofences.
 *
 * This app demonstrates how to create and remove geofences using the GeofencingApi. Uses an
 * BroadcastReceiver to monitor geofence transitions and creates notification and finishes the game
 * when the user enters the final geofence (destination).
 *
 * This app requires a device's Location settings to be turned on. It also requires
 * the ACCESS_FINE_LOCATION permission and user consent. For geofences to work
 * in Android Q, app also needs the ACCESS_BACKGROUND_LOCATION permission and user consent.
 */

open class HuntMainActivity : AppCompatActivity(), BottomNavigationView.OnNavigationItemSelectedListener, OnClickListener,
    LocationListener {

    private lateinit var binding: ActivityHuntMainBinding
    private lateinit var geofencingClient: GeofencingClient
    private lateinit var viewModel: GeofenceViewModel
    lateinit var fragSistem:FragmentSistem
    lateinit var fragMaps:FragmentMaps
    lateinit var ft: FragmentTransaction
    lateinit var preferences: SharedPreferences
    private lateinit var isDialog: AlertDialog
    lateinit var locationManager: LocationManager
    private lateinit var alertDialog: AlertDialog
    val RC_PROSES_SUKSES : Int = 111
    private var isActivityRunning = false

    var status = ""
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    val absen = "http://192.168.43.57/simaptapkl/public/service/absensi.php"
    val jumlah = "http://192.168.43.57/simaptapkl/public/service/jumlah.php"
    val user = "http://192.168.43.57/simaptapkl/public/service/akun.php"
    val konfigurasi = "http://192.168.43.57/simaptapkl/public/service/konfigurasi.php"
    val lokasi = "http://192.168.43.57/simaptapkl/public/service/logpos.php"
    var latitude = ""
    var longitude = ""
    var radius = ""
    var airLoc : AirLocation? = null
    private val CHANNEL_ID = "channel_id_example_1"
    private val notificationId = 101
    var lihat = "0"
    var fragmenlatitude = ""
    var fragmenlongitude = ""
    var fragmenradius = ""
    var fragmenjudul = ""
    var cekKoneksi = "1"
    var cekGPS = "1"
    lateinit var isInternet: AlertDialog
    lateinit var isGPS: AlertDialog
    lateinit var isInternetGPS: AlertDialog

    // TODO: Step 2 add in variable to check if device is running Q or later
    private val runningQOrLater = android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.Q

    // A PendingIntent for the Broadcast Receiver that handles geofence transitions.
    // TODO: Step 8 add in a pending intent
    private val geofencePendingIntent: PendingIntent by lazy {
        val intent = Intent(this, GeofenceBroadcastReceiver::class.java)
        intent.action = ACTION_GEOFENCE_EVENT
        PendingIntent.getBroadcast(this, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)
    }

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.cardView2->{
                if(status.equals("aktif")){
                    waktucekin()
                }else{
                    Toast.makeText(this,"Status PKL anda sudah selesai",Toast.LENGTH_LONG).show()
                }
            }

            R.id.cardView5->{
                if(status.equals("aktif")){
                    waktucekout()
                }else{
                    Toast.makeText(this,"Status PKL anda sudah selesai",Toast.LENGTH_LONG).show()
                }
            }

            R.id.imageView17->{
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
            }

            R.id.profile_image->{
                foto()

                lihat = "1"
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

            R.id.btnHistori->{
                val intent = Intent(this, HistoriActivity::class.java)
                startActivityForResult(intent,RC_PROSES_SUKSES)
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InRight())
                locationManager.removeUpdates(this)
            }

            R.id.btnIzin->{
                val intent = Intent(this, IzinActivity::class.java)
                intent.putExtra("status", status)
                startActivityForResult(intent,RC_PROSES_SUKSES)
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InRight())
                locationManager.removeUpdates(this)
            }
        }
    }

    override fun onNavigationItemSelected(item: MenuItem): Boolean {
        when(item?.itemId){
            R.id.btnBeranda->{
                refresh()
                frameLayout.visibility = View.GONE
                profile_image.visibility = View.VISIBLE
                cardView2.visibility = View.VISIBLE
                cardView5.visibility = View.VISIBLE
                btnHistori.visibility = View.VISIBLE
                btnIzin.visibility = View.VISIBLE
                refreshHome.isEnabled = true
                window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
                window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_VISIBLE
            }

            R.id.btnMaps->{
                ft = supportFragmentManager.beginTransaction()
                ft.replace(R.id.frameLayout,fragMaps).commit()
                frameLayout.setBackgroundColor(Color.argb(255,255,255,255))
                frameLayout.visibility = View.VISIBLE
                refreshHome.isEnabled = false

                val bundle = Bundle()
                var paket : Bundle? = intent.extras
                var notifikasi = paket?.getString("notifikasi").toString()

                if(notifikasi.equals("1")){
                    bundle.putString("latitude", fragmenlatitude)
                    bundle.putString("longitude", fragmenlongitude)
                    bundle.putString("radius", fragmenradius)
                    bundle.putString("title", fragmenjudul)
                }else{
                    var paket : Bundle? = intent.extras
                    val lat = paket?.getString("latitude")
                    val long = paket?.getString("longitude")
                    val rad = paket?.getString("radius")
                    val judul = paket?.getString("judul")
                    bundle.putString("latitude", lat)
                    bundle.putString("longitude", long)
                    bundle.putString("radius", rad)
                    bundle.putString("title", judul)
                }

                fragMaps.arguments = bundle

                window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
                window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
            }

            R.id.btnSistem->{
                ft = supportFragmentManager.beginTransaction()
                ft.replace(R.id.frameLayout,fragSistem).commit()
                frameLayout.setBackgroundColor(Color.argb(255,255,255,255))
                frameLayout.visibility = View.VISIBLE
                profile_image.visibility = View.GONE
                cardView2.visibility = View.GONE
                cardView5.visibility = View.GONE
                btnHistori.visibility = View.GONE
                btnIzin.visibility = View.GONE
                refreshHome.isEnabled = false

                window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
                window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
            }
        }
        return true
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

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = DataBindingUtil.setContentView(this, R.layout.activity_hunt_main)
        viewModel = ViewModelProviders.of(this, SavedStateViewModelFactory(this.application,
            this)).get(GeofenceViewModel::class.java)
        binding.viewmodel = viewModel
        binding.lifecycleOwner = this
        // TODO: Step 9 instantiate the geofencing client
        geofencingClient = LocationServices.getGeofencingClient(this)
        locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
        supportActionBar?.hide()

        checkPermissionsAndStartGeofencing()
//        createChannel(this)

        btnNav.setOnNavigationItemSelectedListener(this)

        cardView2.setOnClickListener(this)
        cardView5.setOnClickListener(this)
        profile_image.setOnClickListener(this)

        imageView17.setOnClickListener(this)
        cr.setOnClickListener(this)

        btnHistori.setOnClickListener(this)
        btnIzin.setOnClickListener(this)
        fragSistem = FragmentSistem()
        fragMaps = FragmentMaps()

        akun()
        hadir()
        telat()
        izin()

        createNotificationChannel()
        enableUserLocation()
        refreshUp()

        var paket : Bundle? = intent.extras
        var notifikasi = paket?.getString("notifikasi").toString()

        if(notifikasi.equals("1")){
            fragmenlokasi()
        }

        val networkConnection = NetworkConnection(applicationContext)
        networkConnection.observe(this, { isConnected ->
            if (isConnected) {
                if(cekKoneksi.equals("0")){
                    if(cekGPS.equals("0")){
                        isDismissInternetGPS()
                        startLoadingGPS()

                        cekKoneksi = "1"
                    }else{
                        isDismissInternet()
                        cekKoneksi = "1"
                    }
                }else{
                    cekKoneksi = "1"
                }
            } else {
                if(cekGPS.equals("0")){
                    cekKoneksi = "0"
                    isDismissGPS()
                    startLoadingInternetGPS()
                }else{
                    cekKoneksi = "0"
                    startLoadingInternet()
                }
            }
        })

        isActivityRunning = true
    }

    fun startLoadingInternet(){
        val inflater = this.layoutInflater
        val dialogView = inflater.inflate(R.layout.circle_loading_internet,null)

        val builder = AlertDialog.Builder(this)
        builder.setView(dialogView)
        builder.setCancelable(false)
        isInternet = builder.create()
        isInternet.window?.setBackgroundDrawableResource(android.R.color.transparent)
        isInternet.show()
    }

    fun isDismissInternet(){
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                isInternet.dismiss()
            }
        },2000)
    }

    fun startLoadingGPS(){
        val inflater = this.layoutInflater
        val dialogView = inflater.inflate(R.layout.circle_loading_gps,null)

        val builder = AlertDialog.Builder(this)
        builder.setView(dialogView)
        builder.setCancelable(false)
        isGPS = builder.create()
        isGPS.window?.setBackgroundDrawableResource(android.R.color.transparent)
        isGPS.show()
    }

    fun isDismissGPS(){
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                isGPS.dismiss()
            }
        },2000)
    }

    fun startLoadingInternetGPS(){
        val inflater = this.layoutInflater
        val dialogView = inflater.inflate(R.layout.circle_loading_internet_gps,null)

        val builder = AlertDialog.Builder(this)
        builder.setView(dialogView)
        builder.setCancelable(false)
        isInternetGPS = builder.create()
        isInternetGPS.window?.setBackgroundDrawableResource(android.R.color.transparent)
        isInternetGPS.show()
    }

    fun isDismissInternetGPS(){
        val handler = Handler()
        handler.postDelayed(object:Runnable{
            override fun run() {
                isInternetGPS.dismiss()
            }
        },2000)
    }

    private fun refreshUp(){
        refreshHome.setColorSchemeColors(
            Color.parseColor("#5AA0FF")
        )
        refreshHome.setOnRefreshListener {
            Handler().postDelayed(Runnable {
                hadir()
                telat()
                izin()
                refreshHome.isRefreshing = false
            }, 2000)
        }
    }

    override fun onLocationChanged(location: Location) {

    }

    override fun onStatusChanged(provider: String?, status: Int, extras: Bundle?) {
        // handle location status changes
    }

    override fun onProviderEnabled(provider: String) {
        // handle provider enabled
        if(cekGPS.equals("0")){
            if(cekKoneksi.equals("1")){
                if (isActivityRunning) {
                    isDismissGPS()
                }
            }else{
                isDismissInternetGPS()
                startLoadingInternet()
            }
        }

        cekGPS = "1"
    }

    override fun onProviderDisabled(provider: String) {
        // handle provider disabled
        if(cekGPS.equals("1")){
            if(cekKoneksi.equals("1")){
                if (isActivityRunning) {
                    startLoadingGPS()
                }
            }else{
                isDismissInternet()
                startLoadingInternetGPS()
            }
        }

        cekGPS = "0"
    }

    fun enableUserLocation(){
        // check for location permission
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED) {

            // check if GPS is enabled
            if (locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
                // request location updates
                locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0f, this)
            }
        }
    }

    private fun createNotificationChannel(){
        if(Build.VERSION.SDK_INT >= Build.VERSION_CODES.O){
            val name = "notification title"
            val descriptionText = "notification description"
            val importance = NotificationManager.IMPORTANCE_HIGH
            val channel = NotificationChannel(CHANNEL_ID,name,importance).apply {
                description = descriptionText
            }

            val notificationManager = getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
            notificationManager.createNotificationChannel(channel)
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
                data.put("pilihan","home")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    /*
 *  When we get the result from asking the user to turn on device location, we call
 *  checkDeviceLocationSettingsAndStartGeofence again to make sure it's actually on, but
 *  we don't resolve the check to keep the user from seeing an endless loop.
 */
    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        airLoc?.onActivityResult(requestCode, resultCode, data)
        super.onActivityResult(requestCode, resultCode, data)

        if (requestCode == REQUEST_TURN_DEVICE_LOCATION_ON) {
            checkDeviceLocationSettingsAndStartGeofence(false)
        }else if(resultCode == Activity.RESULT_OK){
            if (requestCode == RC_PROSES_SUKSES){
                if (data?.extras?.getString("proses").equals("1")) {
                    enableUserLocation()
                }
            }
        }
    }

    /*
     *  When the user clicks on the notification, this method will be called, letting us know that
     *  the geofence has been triggered, and it's time to move to the next one in the treasure
     *  hunt.
     */
    override fun onNewIntent(intent: Intent?) {
        super.onNewIntent(intent)
        val extras = intent?.extras
        if(extras != null){
            if(extras.containsKey(GeofencingConstants.EXTRA_GEOFENCE_INDEX)){
                viewModel.updateHint(extras.getInt(GeofencingConstants.EXTRA_GEOFENCE_INDEX))
                checkPermissionsAndStartGeofencing()
            }
        }
    }

    /*
     * In all cases, we need to have the location permission.  On Android 10+ (Q) we need to have
     * the background permission as well.
     */
    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<String>,
        grantResults: IntArray
    ) {
        airLoc?.onRequestPermissionsResult(requestCode, permissions, grantResults)
        Log.d(TAG, "onRequestPermissionResult")

        if (
            grantResults.isEmpty() ||
            grantResults[LOCATION_PERMISSION_INDEX] == PackageManager.PERMISSION_DENIED ||
            (requestCode == REQUEST_FOREGROUND_AND_BACKGROUND_PERMISSION_RESULT_CODE &&
                    grantResults[BACKGROUND_LOCATION_PERMISSION_INDEX] ==
                    PackageManager.PERMISSION_DENIED))
        {
            Snackbar.make(
                binding.activityMapsMain,
                R.string.permission_denied_explanation,
                Snackbar.LENGTH_INDEFINITE
            )
                .setAction(R.string.settings) {
                    startActivity(Intent().apply {
                        action = Settings.ACTION_APPLICATION_DETAILS_SETTINGS
                        data = Uri.fromParts("package", BuildConfig.APPLICATION_ID, null)
                        flags = Intent.FLAG_ACTIVITY_NEW_TASK
                    })
                }.show()
        } else {
            checkDeviceLocationSettingsAndStartGeofence()
        }
    }

    /**
     * This will also destroy any saved state in the associated ViewModel, so we remove the
     * geofences here.
     */

    override fun onDestroy() {
        super.onDestroy()
        removeGeofences()
        isActivityRunning = false
    }

    /**
     * Starts the permission check and Geofence process only if the Geofence associated with the
     * current hint isn't yet active.
     */
    private fun checkPermissionsAndStartGeofencing() {
        if (viewModel.geofenceIsActive()) return
        if (foregroundAndBackgroundLocationPermissionApproved()) {
            checkDeviceLocationSettingsAndStartGeofence()
        } else {
            requestForegroundAndBackgroundLocationPermissions()
        }
    }

    /*
     *  Uses the Location Client to check the current state of location settings, and gives the user
     *  the opportunity to turn on location services within our app.
     */
    private fun checkDeviceLocationSettingsAndStartGeofence(resolve:Boolean = true) {
        // TODO: Step 6 add code to check that the device's location is on
        val locationRequest = LocationRequest.create().apply {
            priority = LocationRequest.PRIORITY_LOW_POWER
        }
        val builder = LocationSettingsRequest.Builder().addLocationRequest(locationRequest)
        val settingsClient = LocationServices.getSettingsClient(this)
        val locationSettingsResponseTask =
            settingsClient.checkLocationSettings(builder.build())
        locationSettingsResponseTask.addOnFailureListener { exception ->
            if (exception is ResolvableApiException && resolve){
                try {
                    exception.startResolutionForResult(this@HuntMainActivity,
                        REQUEST_TURN_DEVICE_LOCATION_ON)
                } catch (sendEx: IntentSender.SendIntentException) {
                    Log.d(TAG, "Error getting location settings resolution: " + sendEx.message)
                }
            } else {
                startLoading()
                val handler = Handler()
                handler.postDelayed(object:Runnable{
                    override fun run() {
                        isDialog.dismiss()
                        enableUserLocation()
                        checkDeviceLocationSettingsAndStartGeofence()
                    }
                },5000)
            }
        }
        locationSettingsResponseTask.addOnCompleteListener {
            try {
                if ( it.isSuccessful ) {
                    var paket : Bundle? = intent.extras
                    var notifikasi = paket?.getString("notifikasi").toString()

                    if(notifikasi.equals("0")){
                        val service = Intent(this, PesertaService::class.java)
                        service.putExtra("latitude", paket?.getString("latitude").toString())
                        service.putExtra("longitude", paket?.getString("longitude").toString())
                        service.putExtra("radius", paket?.getString("radius").toString())
                        startService(service)
                    }else{
                        lokasi()
                    }
                }
            }catch (e: Exception){

            }
        }
    }

    fun lokasi(){
        val request = object : StringRequest(
            Method.POST,konfigurasi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val lat = jsonObject.getString("LATITUDE_KONF")
                val long = jsonObject.getString("LONGITUDE_KONF")
                val rad = jsonObject.getString("RADIUS_KONF")

                val service = Intent(this, PesertaService::class.java)
                service.putExtra("latitude", lat)
                service.putExtra("longitude", long)
                service.putExtra("radius", rad)
                startService(service)
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

                val service = Intent(this, PesertaService::class.java)
                service.putExtra("latitude", lat)
                service.putExtra("longitude", long)
                service.putExtra("radius", rad)
                startService(service)
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

    /*
     *  Determines whether the app has the appropriate permissions across Android 10+ and all other
     *  Android versions.
     */
    @TargetApi(29)
    private fun foregroundAndBackgroundLocationPermissionApproved(): Boolean {
        val foregroundLocationApproved = (
                PackageManager.PERMISSION_GRANTED ==
                        ActivityCompat.checkSelfPermission(this,
                            Manifest.permission.ACCESS_FINE_LOCATION))
        val backgroundPermissionApproved =
            if (runningQOrLater) {
                PackageManager.PERMISSION_GRANTED ==
                        ActivityCompat.checkSelfPermission(
                            this, Manifest.permission.ACCESS_BACKGROUND_LOCATION
                        )
            } else {
                true
            }
        return foregroundLocationApproved && backgroundPermissionApproved
    }

    /*
     *  Requests ACCESS_FINE_LOCATION and (on Android 10+ (Q) ACCESS_BACKGROUND_LOCATION.
     */
    @TargetApi(29 )
    private fun requestForegroundAndBackgroundLocationPermissions() {
        if (foregroundAndBackgroundLocationPermissionApproved())
            return
        var permissionsArray = arrayOf(Manifest.permission.ACCESS_FINE_LOCATION)
        val resultCode = when {
            runningQOrLater -> {
                permissionsArray += Manifest.permission.ACCESS_BACKGROUND_LOCATION
                REQUEST_FOREGROUND_AND_BACKGROUND_PERMISSION_RESULT_CODE
            }
            else -> REQUEST_FOREGROUND_ONLY_PERMISSIONS_REQUEST_CODE
        }
        Log.d(TAG, "Request foreground only location permission")
        ActivityCompat.requestPermissions(
            this@HuntMainActivity,
            permissionsArray,
            resultCode
        )
    }

    /*
     * Adds a Geofence for the current clue if needed, and removes any existing Geofence. This
     * method should be called after the user has granted the location permission.  If there are
     * no more geofences, we remove the geofence and let the viewmodel know that the ending hint
     * is now "active."
     */

    /**
     * Removes geofences. This method should be called after the user has granted the location
     * permission.
     */
    private fun removeGeofences() {
        // TODO: Step 12 add in code to remove the geofences
    }
    companion object {
        internal const val ACTION_GEOFENCE_EVENT =
            "HuntMainActivity.treasureHunt.action.ACTION_GEOFENCE_EVENT"
    }

    fun waktucekin(){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    airLoc = AirLocation(this,true,true,
                        object : AirLocation.Callbacks{
                            override fun onFailed(locationFailedEnum: AirLocation.LocationFailedEnum) {
                                Toast.makeText(this@HuntMainActivity,"Gagal mendapatkan lokasi saat ini", Toast.LENGTH_LONG).show()
                            }

                            override fun onSuccess(location: Location) {
                                val inflater = layoutInflater
                                val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                                val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                                val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                                val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                                kembali.setText("Kembali")
                                kirim.setText("Kirim")
                                informasi.setText("Apakah anda ingin melakukan absensi masuk ?")

                                kembali.setOnClickListener{
                                    alertDialog.dismiss()
                                }

                                kirim.setOnClickListener{
                                    alertDialog.dismiss()
                                    cekin(getJalan(location.latitude, location.longitude), getDesa(location.latitude, location.longitude), getKecamatan(location.latitude, location.longitude), getKabupaten(location.latitude, location.longitude), getProvinsi(location.latitude, location.longitude), getKodepos(location.latitude, location.longitude))
                                }

                                val builder = android.app.AlertDialog.Builder(this@HuntMainActivity)
                                builder.setView(dialogView)
                                alertDialog = builder.create()
                                alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                                alertDialog.show()
                            }
                        })
                }else if(respon.equals("2")){
                    Toast.makeText(this,"System absensi masuk belum diaktifkan",Toast.LENGTH_LONG).show()
                }else if(respon.equals("3")){
                    Toast.makeText(this,"System absensi masuk dinonaktifkan",Toast.LENGTH_LONG).show()
                }else if(respon.equals("4")){
                    Toast.makeText(this,"Anda sudah melakukan absensi masuk",Toast.LENGTH_LONG).show()
                }else if(respon.equals("5")){
                    Toast.makeText(this,"Anda mengajukan ketidakhadiran hari ini",Toast.LENGTH_LONG).show()
                }else if(respon.equals("10")){
                    Toast.makeText(this,"Hari ini jam kerja libur",Toast.LENGTH_LONG).show()
                }else if(respon.equals("11")){
                    Toast.makeText(this,"Status PKL anda sudah selesai",Toast.LENGTH_LONG).show()
                }else if(respon.equals("12")){
                    Toast.makeText(this,"Hari libur nasional",Toast.LENGTH_LONG).show()
                }else if(respon.equals("13")){
                    Toast.makeText(this,"PKL anda belum dimulai",Toast.LENGTH_LONG).show()
                }else{
                    val inflater = layoutInflater
                    val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                    val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                    val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                    val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                    kembali.setText("Kembali")
                    kirim.setText("Hapus")
                    informasi.setText("Surat izin yang anda ajukan ditolak. Apakah anda ingin menghapus surat izin dan melakukan absensi kehadiran ?")

                    kembali.setOnClickListener{
                        alertDialog.dismiss()
                    }

                    kirim.setOnClickListener{
                        alertDialog.dismiss()
                        airLoc = AirLocation(this,true,true,
                            object : AirLocation.Callbacks{
                                override fun onFailed(locationFailedEnum: AirLocation.LocationFailedEnum) {
                                    Toast.makeText(this@HuntMainActivity,"Gagal mendapatkan lokasi saat ini", Toast.LENGTH_LONG).show()
                                }

                                override fun onSuccess(location: Location) {
                                    izinIn(
                                        getJalan(location.latitude, location.longitude),
                                        getDesa(location.latitude, location.longitude),
                                        getKecamatan(location.latitude, location.longitude),
                                        getKabupaten(location.latitude, location.longitude),
                                        getProvinsi(location.latitude, location.longitude),
                                        getKodepos(location.latitude, location.longitude)
                                    )
                                }
                            })
                    }

                    val builder = android.app.AlertDialog.Builder(this@HuntMainActivity)
                    builder.setView(dialogView)
                    alertDialog = builder.create()
                    alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                    alertDialog.show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","jamcekin")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun cekin(jln:String, ds:String, kec:String, kab:String, prov:String, kp:String){
        val request = object : StringRequest(
            Method.POST,absen,
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
                            Toast.makeText(this@HuntMainActivity,"Berhasil absensi masuk",Toast.LENGTH_LONG).show()
                            telat()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","cekin")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("jalan",jln)
                data.put("desa",ds)
                data.put("kecamatan",kec)
                data.put("kabupaten",kab)
                data.put("provinsi",prov)
                data.put("kodepos",kp)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun izinIn(jln:String, ds:String, kec:String, kab:String, prov:String, kp:String){
        val request = object : StringRequest(
            Method.POST,absen,
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
                            Toast.makeText(this@HuntMainActivity,"Berhasil absensi masuk",Toast.LENGTH_LONG).show()
                            telat()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","izinin")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("jalan",jln)
                data.put("desa",ds)
                data.put("kecamatan",kec)
                data.put("kabupaten",kab)
                data.put("provinsi",prov)
                data.put("kodepos",kp)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun waktucekout(){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    airLoc = AirLocation(this,true,true,
                        object : AirLocation.Callbacks{
                            override fun onFailed(locationFailedEnum: AirLocation.LocationFailedEnum) {
                                Toast.makeText(this@HuntMainActivity,"Gagal mendapatkan lokasi saat ini", Toast.LENGTH_LONG).show()
                            }

                            override fun onSuccess(location: Location) {
                                val inflater = layoutInflater
                                val dialogView = inflater.inflate(R.layout.activity_form_pulang,null)
                                val formKegiatan = dialogView.findViewById<TextInputEditText>(R.id.formKegiatan)
                                val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                                val kembali = dialogView.findViewById<TextView>(R.id.textView129)
                                val kirim = dialogView.findViewById<TextView>(R.id.textView128)

                                kembali.setText("Kembali")
                                kirim.setText("Kirim")
                                informasi.setText("Apakah anda yakin ingin melakukan absensi pulang ?")

                                kembali.setOnClickListener{
                                    alertDialog.dismiss()
                                }

                                kirim.setOnClickListener{
                                    if(formKegiatan.text.toString().equals("")){
                                        Toast.makeText(this@HuntMainActivity, "Form kegiatan kosong", Toast.LENGTH_SHORT).show()
                                    }else {
                                        alertDialog.dismiss()
                                        cekout(
                                            getJalan(location.latitude, location.longitude),
                                            getDesa(location.latitude, location.longitude),
                                            getKecamatan(location.latitude, location.longitude),
                                            getKabupaten(location.latitude, location.longitude),
                                            getProvinsi(location.latitude, location.longitude),
                                            getKodepos(location.latitude, location.longitude),
                                            formKegiatan.text.toString()
                                        )
                                    }
                                }

                                val builder = android.app.AlertDialog.Builder(this@HuntMainActivity)
                                builder.setView(dialogView)
                                alertDialog = builder.create()
                                alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                                alertDialog.show()
                            }
                        })
                }else if(respon.equals("2")){
                    Toast.makeText(this,"System absensi pulang belum diaktifkan",Toast.LENGTH_LONG).show()
                }else if(respon.equals("3")){
                    Toast.makeText(this,"System absensi pulang dinonaktifkan",Toast.LENGTH_LONG).show()
                }else if(respon.equals("4")){
                    Toast.makeText(this,"Anda belum melakukan absensi masuk",Toast.LENGTH_LONG).show()
                }else if(respon.equals("5")){
                    Toast.makeText(this,"Anda mengajukan ketidakhadiran hari ini",Toast.LENGTH_LONG).show()
                }else if(respon.equals("6")) {
                    Toast.makeText(this, "Anda sudah melakukan absensi pulang", Toast.LENGTH_LONG).show()
                }else if(respon.equals("10")){
                    Toast.makeText(this,"Hari ini jam kerja libur",Toast.LENGTH_LONG).show()
                }else if(respon.equals("11")){
                    Toast.makeText(this,"Status PKL anda sudah selesai",Toast.LENGTH_LONG).show()
                }else if(respon.equals("12")){
                    Toast.makeText(this,"Hari libur nasional",Toast.LENGTH_LONG).show()
                }else if(respon.equals("13")){
                    Toast.makeText(this,"PKL anda belum dimulai",Toast.LENGTH_LONG).show()
                }else if(respon.equals("30")){
                    val inflater = layoutInflater
                    val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                    val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                    val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                    val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                    kembali.setText("Kembali")
                    kirim.setText("Kirim")
                    informasi.setText("System absensi pulang sudah dinonaktifkan. Silahkan kirim pesan kepada pembimbing untuk memperbarui absensi pulang.")

                    kembali.setOnClickListener{
                        alertDialog.dismiss()
                    }

                    kirim.setOnClickListener{
                        alertDialog.dismiss()
                        val loading = LoadingDialog(this)
                        loading.startLoading()
                        val handler = Handler()
                        handler.postDelayed(object:Runnable{
                            override fun run() {
                                loading.isDismiss()
                                kirimNotifikasi()
                                Toast.makeText(this@HuntMainActivity,"Berhasil terkitim",Toast.LENGTH_LONG).show()
                            }
                        },3000)
                    }

                    val builder = android.app.AlertDialog.Builder(this@HuntMainActivity)
                    builder.setView(dialogView)
                    alertDialog = builder.create()
                    alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                    alertDialog.show()
                }else{
                    val inflater = layoutInflater
                    val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                    val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                    val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                    val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                    kembali.setText("Kembali")
                    kirim.setText("Hapus")
                    informasi.setText("Surat izin yang anda ajukan ditolak. Apakah anda ingin menghapus surat izin dan melakukan absensi kehadiran ?")

                    kembali.setOnClickListener{
                        alertDialog.dismiss()
                    }

                    kirim.setOnClickListener{
                        alertDialog.dismiss()
                        izinOut()
                    }

                    val builder = android.app.AlertDialog.Builder(this@HuntMainActivity)
                    builder.setView(dialogView)
                    alertDialog = builder.create()
                    alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                    alertDialog.show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","jamcekout")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun cekout(jln:String, ds:String, kec:String, kab:String, prov:String, kp:String, kegiatan:String){
        val request = object : StringRequest(
            Method.POST,absen,
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
                            Toast.makeText(this@HuntMainActivity,"Berhasil absensi pulang",Toast.LENGTH_LONG).show()
                            hadir()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","cekout")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("jalan",jln)
                data.put("desa",ds)
                data.put("kecamatan",kec)
                data.put("kabupaten",kab)
                data.put("provinsi",prov)
                data.put("kodepos",kp)
                data.put("kegiatan",kegiatan)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun izinOut(){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("0")){
                    Toast.makeText(this,"Anda belum melakukan absensi masuk",Toast.LENGTH_LONG).show()
                    hadir()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","izinout")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun telat(){
        val request = object : StringRequest(
            Method.POST,jumlah,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val total = jsonObject.getString("jumlah")

                textView9.setText(total)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","telat")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun getJalan(lat:Double, long:Double): String{
        var namaJalan = ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var jalan = geocoder.getFromLocation(lat, long,1)

        try {
            namaJalan = jalan.get(0).thoroughfare
        }catch (e: Exception){
            namaJalan
        }

        return namaJalan
    }

    fun getDesa(lat:Double, long:Double): String{
        var namaDesa = ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var desa = geocoder.getFromLocation(lat, long,1)

        try {
            namaDesa = desa.get(0).subLocality
        }catch (e: Exception){
            namaDesa
        }

        return namaDesa
    }

    fun getKecamatan(lat:Double, long:Double): String{
        var namaKecamatan = ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var kecamatan = geocoder.getFromLocation(lat, long,1)

        try {
            namaKecamatan = kecamatan.get(0).locality
        }catch (e: Exception){
            namaKecamatan
        }

        return namaKecamatan
    }

    fun getKabupaten(lat:Double, long:Double): String{
        var namaKabupaten = ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var kabupaten = geocoder.getFromLocation(lat, long,1)

        try {
            namaKabupaten = kabupaten.get(0).subAdminArea
        }catch (e: Exception){
            namaKabupaten
        }

        return namaKabupaten
    }

    fun getProvinsi(lat:Double, long:Double): String{
        var namaProvinsi= ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var provinsi = geocoder.getFromLocation(lat, long,1)

        try {
            namaProvinsi = provinsi.get(0).adminArea
        }catch (e: Exception){
            namaProvinsi
        }

        return namaProvinsi
    }

    fun getKodepos(lat:Double, long:Double): String{
        var namaKodepos= ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var kodePos = geocoder.getFromLocation(lat, long,1)

        try {
            namaKodepos = kodePos.get(0).postalCode
        }catch (e: Exception){
            namaKodepos
        }

        return namaKodepos
    }

    fun kirimNotifikasi(){
        val client = AsyncHttpClient()
        val params = RequestParams()
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        params.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
        params.put("pilihan", "perbaruiabsenpulang")
        params.put("event", "perbarui")

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

    fun akun(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val mahasiswa = jsonObject.getString("NAMA_PST")
                val kampus = jsonObject.getString("NAMA_ASAL")
                val fotoProfil = jsonObject.getString("url")
                val statusMahasiswa = jsonObject.getString("STATUS_PST")

                status = statusMahasiswa
                textView3.setText(mahasiswa)
                textView4.setText(kampus)
                Picasso.get().load(fotoProfil).into(profile_image)

                cekCekin()
                cekCekout()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","home")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun refresh(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val mahasiswa = jsonObject.getString("NAMA_PST")
                val kampus = jsonObject.getString("NAMA_ASAL")
                val fotoProfil = jsonObject.getString("url")

                textView3.setText(mahasiswa)
                textView4.setText(kampus)
                Picasso.get().load(fotoProfil).into(profile_image)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","home")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun cekCekin(){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")) {
                    notifikasicekin()
                }else{ }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","ceknotifikasicekin")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    private fun notifikasicekin(){
        val builder = NotificationCompat.Builder(this, CHANNEL_ID)
            .setSmallIcon(R.drawable.kerangka)
            .setContentTitle("Absensi Masuk")
            .setContentText("Hari ini anda belum melakukan absensi masuk")
            .setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION))
            .setPriority(NotificationCompat.PRIORITY_DEFAULT)
            .setCategory(NotificationCompat.CATEGORY_MESSAGE)
        with(NotificationManagerCompat.from(this)) {
            notify(notificationId, builder.build())
        }
    }

    fun cekCekout(){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")) {
                    notifikasicekout()
                }else{}
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","ceknotifikasicekout")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    private fun notifikasicekout(){
        val builder = NotificationCompat.Builder(this, CHANNEL_ID)
            .setSmallIcon(R.drawable.kerangka)
            .setContentTitle("Absensi Pulang")
            .setContentText("Hari ini anda belum melakukan absensi pulang")
            .setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION))
            .setPriority(NotificationCompat.PRIORITY_DEFAULT)
            .setCategory(NotificationCompat.CATEGORY_MESSAGE)
        with(NotificationManagerCompat.from(this)) {
            notify(notificationId, builder.build())
        }
    }

    fun hadir(){
        val request = object : StringRequest(
            Method.POST,jumlah,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val total = jsonObject.getString("jumlah")

                textView8.setText(total)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","hadir")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun izin(){
        val request = object : StringRequest(
            Method.POST,jumlah,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val total = jsonObject.getString("jumlah")

                textView10.setText(total)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","izin")
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

private const val REQUEST_FOREGROUND_AND_BACKGROUND_PERMISSION_RESULT_CODE = 33
private const val REQUEST_FOREGROUND_ONLY_PERMISSIONS_REQUEST_CODE = 34
private const val REQUEST_TURN_DEVICE_LOCATION_ON = 29
private const val TAG = "HuntMainActivity"
private const val LOCATION_PERMISSION_INDEX = 0
private const val BACKGROUND_LOCATION_PERMISSION_INDEX = 1
