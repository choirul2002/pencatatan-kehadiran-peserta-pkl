package com.example.android.treasureHunt

import android.Manifest
import android.app.Activity
import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.os.Bundle
import android.os.Handler
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import kotlinx.android.synthetic.main.activity_kebijakan_aplikasi.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions

class ActivityKebijakan:AppCompatActivity(), View.OnClickListener, LocationListener {
    lateinit var isDialog: AlertDialog
    private lateinit var locationManager: LocationManager
    var proses = ""
    var cekKoneksi = "1"
    var cekGPS = "1"
    lateinit var isInternet: AlertDialog
    lateinit var isGPS: AlertDialog
    lateinit var isInternetGPS: AlertDialog
    private var isActivityRunning = false

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.imageView14->{
                proses = "1"
                finish()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
                locationManager.removeUpdates(this)
            }
        }
    }

    override fun onBackPressed() {
        proses = "1"
        super.onBackPressed()
        val transitions = Transitions(this)
        transitions.setAnimation(Fade().InLeft())
        locationManager.removeUpdates(this)
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_kebijakan_aplikasi)

        supportActionBar?.hide()
        window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager
        imageView14.setOnClickListener(this)
        enableUserLocation()

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

    override fun onDestroy() {
        super.onDestroy()
        isActivityRunning = false
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

    override fun finish() {
        var intent = Intent()

        if(!proses.equals("")){
            intent.putExtra("proses", proses)
            setResult(Activity.RESULT_OK,intent)
        }

        super.finish()
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

    private fun enableUserLocation(){
        // check for location permission
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED) {

            // check if GPS is enabled
            if (locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
                // request location updates
                locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0f, this)
            }
        }
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