package com.example.android.treasureHunt

import android.app.Activity
import android.content.Context
import android.content.Intent
import android.content.SharedPreferences
import android.os.Bundle
import android.os.Handler
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.fragment.app.Fragment
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import kotlinx.android.synthetic.main.fragment_sistem.view.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import org.json.JSONObject
import java.util.HashMap

class FragmentSistem:Fragment() {
    lateinit var preferences: SharedPreferences
    lateinit var thisParent:HuntMainActivity
    lateinit var v:View
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    val RC_PROSES_SUKSES : Int = 111
    private lateinit var alertDialog: android.app.AlertDialog
    val lokasi = "http://192.168.43.57/simaptapkl/public/service/logpos.php"

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)

        if(resultCode == Activity.RESULT_OK){
            if (requestCode == RC_PROSES_SUKSES){
                if (data?.extras?.getString("proses").equals("1")) {
                    thisParent.enableUserLocation()
                }
            }
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        thisParent = activity as HuntMainActivity
        v = inflater.inflate(R.layout.fragment_sistem,container,false)

        v.cardView6.setOnClickListener { v: View ->
            val intent = Intent(v.context, EditProfilActivity::class.java)
            startActivityForResult(intent,RC_PROSES_SUKSES)
            val transitions = Transitions(thisParent)
            transitions.setAnimation(Fade().InRight())
            thisParent.locationManager.removeUpdates(thisParent)
        }

        v.cardView10.setOnClickListener { v: View ->
            val intent = Intent(v.context, ActivityKebijakan::class.java)
            startActivityForResult(intent,RC_PROSES_SUKSES)
            val transitions = Transitions(thisParent)
            transitions.setAnimation(Fade().InRight())
            thisParent.locationManager.removeUpdates(thisParent)
        }

        v.logout.setOnClickListener { v: View ->
            val inflater = layoutInflater
            val dialogView = inflater.inflate(R.layout.custom_dialog,null)
            val informasi = dialogView.findViewById<TextView>(R.id.textView127)
            val kirim = dialogView.findViewById<TextView>(R.id.textView128)
            val kembali = dialogView.findViewById<TextView>(R.id.textView129)

            kembali.setText("Kembali")
            kirim.setText("Keluar")
            informasi.setText("Apakah anda yakin ingin logout akun ini dari sistem ?")

            kembali.setOnClickListener{
                alertDialog.dismiss()
            }

            kirim.setOnClickListener{
                alertDialog.dismiss()

                val loading = LoadingDialog(thisParent)
                loading.startLoading()
                val handler = Handler()
                handler.postDelayed(object:Runnable{
                    override fun run() {
                        loading.isDismiss()
                        thisParent.locationManager.removeUpdates(thisParent)

                        val service = Intent(thisParent, PesertaService::class.java)
                        thisParent.stopService(service)

                        hapusLokasi()
                    }
                },3000)
            }

            val builder = android.app.AlertDialog.Builder(thisParent)
            builder.setView(dialogView)
            alertDialog = builder.create()
            alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
            alertDialog.show()
        }

        return v
    }

    fun hapusLokasi(){
        val request = object : StringRequest(
            Method.POST,lokasi,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                    val prefEditor = preferences.edit()
                    prefEditor.putString(ID_AKUN,null)
                    prefEditor.commit()

                    val intent = Intent(v.context,LoginActivity::class.java)
                    v.context.startActivity(intent)
                    Animatoo.animateFade(thisParent)
                    thisParent.finishAffinity()
                }else{
                    preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                    val prefEditor = preferences.edit()
                    prefEditor.putString(ID_AKUN,null)
                    prefEditor.commit()

                    val intent = Intent(v.context,LoginActivity::class.java)
                    v.context.startActivity(intent)
                    Animatoo.animateFade(thisParent)
                    thisParent.finishAffinity()
                }
            },
            Response.ErrorListener { error ->

            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String, String>()
                data.put("pilihan","hapuslokasi")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(thisParent)
        queue.add(request)
    }
}