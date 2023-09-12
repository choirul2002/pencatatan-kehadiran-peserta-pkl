package com.example.android.treasureHunt


import android.app.AlertDialog
import android.content.Intent
import android.os.Bundle
import android.os.Handler
import android.view.View
import android.widget.LinearLayout
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import com.blogspot.atifsoftwares.animatoolib.Animatoo
//import com.thecode.onboardingviewagerexamples.R
import kotlinx.android.synthetic.main.activity_onboarding_finish.*

class OnboardingFinishActivity : AppCompatActivity(), View.OnClickListener {
    var cekKoneksi = "1"
    private lateinit var isDialog: AlertDialog

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.button->{
                val intent = Intent(this, LoginActivity::class.java)
                startActivity(intent)
                Animatoo.animateSlideLeft(this)
                finish()
            }
        }
    }

    private lateinit var btnStart: LinearLayout

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_onboarding_finish)

        supportActionBar?.hide()
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)

        button.setOnClickListener(this)

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
}
