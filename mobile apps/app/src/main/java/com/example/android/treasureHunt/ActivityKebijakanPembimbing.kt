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
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import kotlinx.android.synthetic.main.activity_kebijakan_aplikasi.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions

class ActivityKebijakanPembimbing:AppCompatActivity(), View.OnClickListener {
    lateinit var isDialog: AlertDialog
    private lateinit var locationManager: LocationManager
    var proses = ""
    var cekKoneksi = "1"

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.imageView14->{
                proses = "1"
                finish()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
            }
        }
    }

    override fun onBackPressed() {
        proses = "1"
        super.onBackPressed()
        val transitions = Transitions(this)
        transitions.setAnimation(Fade().InLeft())
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_kebijakan_aplikasi)

        supportActionBar?.hide()
        window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager
        imageView14.setOnClickListener(this)

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

    override fun finish() {
        var intent = Intent()

        if(!proses.equals("")){
            intent.putExtra("proses", proses)
            setResult(Activity.RESULT_OK,intent)
        }

        super.finish()
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