package com.example.android.treasureHunt

import android.Manifest
import android.app.ActivityManager
import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.net.Uri
import android.os.Bundle
import android.os.Handler
import android.view.View
import android.widget.Spinner
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import kotlinx.android.synthetic.main.coba2.*
import android.app.AppOpsManager
import android.content.ActivityNotFoundException
import android.os.Build
import android.provider.ContactsContract.Data
import android.provider.Settings
import android.util.Log
import androidx.recyclerview.widget.LinearLayoutManager
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import org.json.JSONArray
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

class Coba2: AppCompatActivity(), View.OnClickListener{
    private val api by lazy {ApiRetrofit().endpoint}

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.button8->{

            }
        }
    }
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.coba2)

        window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        button8.setOnClickListener(this)

        api.getData().enqueue(object : Callback<DataModel>{
            override fun onResponse(
                call: Call<DataModel>,
                response: retrofit2.Response<DataModel>
            ) {
                if(response.isSuccessful){
                    val listdata = response.body()!!.datas
                    listdata.forEach{
                        Log.e("Coba2", "nama ${it.nama}")
                    }
                }
            }

            override fun onFailure(call: Call<DataModel>, t: Throwable) {
                Log.e("Coba2", t.toString())
            }
        })
    }
}