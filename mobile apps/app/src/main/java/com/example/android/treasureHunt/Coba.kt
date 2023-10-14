package com.example.android.treasureHunt

import android.Manifest
import android.app.Activity
import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.content.pm.PackageManager
import android.graphics.Color
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.net.Uri
import android.os.Handler
import android.provider.Settings
import android.util.Log
import android.view.View
import android.view.ViewTreeObserver
import android.widget.Toast
import androidx.core.app.ActivityCompat
import androidx.core.app.ActivityOptionsCompat
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import com.google.android.material.bottomsheet.BottomSheetDialog
import kotlinx.android.synthetic.main.activity_izin.*
import kotlinx.android.synthetic.main.coba.*
import kotlinx.android.synthetic.main.coba.lsForum
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import org.json.JSONArray

class Coba: AppCompatActivity(), View.OnClickListener {


    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.coba)

        window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR

    }

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.button2->{

            }
            R.id.button3->{

            }
            R.id.button4->{

            }
            R.id.button5->{

            }
        }
    }
}