package com.example.android.treasureHunt

import android.content.Context
import android.content.DialogInterface
import android.content.Intent
import android.content.SharedPreferences
import android.os.Bundle
import android.os.Handler
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.fragment.app.Fragment
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import kotlinx.android.synthetic.main.fragment_sistem_pem.view.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions

class FragmentSistemPem:Fragment() {
    lateinit var preferences: SharedPreferences
    lateinit var thisParent:HomePembimbingActivity
    lateinit var v:View
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    private lateinit var alertDialog: android.app.AlertDialog

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        thisParent = activity as HomePembimbingActivity
        v = inflater.inflate(R.layout.fragment_sistem_pem,container,false)

        v.cardView6.setOnClickListener { v: View ->
            val intent = Intent(v.context, EditProfilPembimbingActivity::class.java)
            startActivity(intent)
            val transitions = Transitions(thisParent)
            transitions.setAnimation(Fade().InRight())
        }

        v.cardView10.setOnClickListener { v: View ->
            val intent = Intent(v.context, ActivityKebijakanPembimbing::class.java)
            startActivity(intent)
            val transitions = Transitions(thisParent)
            transitions.setAnimation(Fade().InRight())
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

                        val service = Intent(thisParent, PembimbingService::class.java)
                        thisParent.stopService(service)

                        if(thisParent.cektimer.equals("1")){
                            thisParent.timer.cancel()
                        }

                        preferences = thisParent.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                        val prefEditor = preferences.edit()
                        prefEditor.putString(ID_AKUN,null)
                        prefEditor.commit()

                        val intent = Intent(v.context,LoginActivity::class.java)
                        v.context.startActivity(intent)
                        Animatoo.animateFade(thisParent)
                        thisParent.finishAffinity()
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

}