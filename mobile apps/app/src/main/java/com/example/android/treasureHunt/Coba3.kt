package com.example.android.treasureHunt

import android.Manifest
import android.animation.Animator
import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.app.Activity
import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.graphics.drawable.ColorDrawable
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.os.Bundle
import android.os.Handler
import android.util.Log
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import com.pusher.client.Pusher
import com.pusher.client.PusherOptions
import com.pusher.client.connection.ConnectionEventListener
import com.pusher.client.connection.ConnectionState
import com.pusher.client.connection.ConnectionStateChange
import kotlinx.android.synthetic.main.activity_histori.*
import kotlinx.android.synthetic.main.coba3.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import org.json.JSONObject

class Coba3:AppCompatActivity(), View.OnClickListener, LocationListener {
    var proses = ""
    private lateinit var isDialog: AlertDialog
    private lateinit var locationManager: LocationManager

    override fun onClick(v: View?) {
        when(v?.id){

        }
    }
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.coba3)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
//        locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager
        window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
//        enableUserLocation()
    }

//    override fun onBackPressed() {
//        proses = "3"
////        val transitions = Transitions(this)
//        super.onBackPressed()
//        val transitions = Transitions(this)
//        transitions.setAnimation(Fade().InLeft())
//        locationManager.removeUpdates(this)
//    }


//    override fun onSupportNavigateUp(): Boolean {
//        proses = "2"
//        finish()
//        val transitions = Transitions(this)
//        transitions.setAnimation(Fade().InLeft())
//        locationManager.removeUpdates(this)
//        return true
//    }

//    override fun finish() {
//        var intent = Intent()
//
//        if(!proses.equals("")){
//            intent.putExtra("proses", proses)
//            setResult(Activity.RESULT_OK,intent)
//        }else{
//            intent.putExtra("proses", "0")
//            setResult(Activity.RESULT_OK,intent)
//        }
//
//        super.finish()
//    }

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

    override fun onLocationChanged(location: Location) {
        val latitude = location.latitude
        val longitude = location.longitude
//        Toast.makeText(this, latitude.toString()+" , "+longitude.toString(), Toast.LENGTH_SHORT).show()
    }

    override fun onStatusChanged(provider: String?, status: Int, extras: Bundle?) {
        // handle location status changes
    }

    override fun onProviderEnabled(provider: String) {
        // handle provider enabled
        isDismiss()
    }

    override fun onProviderDisabled(provider: String) {
        // handle provider disabled
        startLoading()
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