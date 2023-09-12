package com.example.android.treasureHunt

import android.Manifest
import android.animation.Animator
import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.app.Activity
import android.content.Context
import android.content.DialogInterface
import android.content.Intent
import android.content.SharedPreferences
import android.net.Uri
import android.os.Bundle
import android.os.Handler
import android.provider.MediaStore
import android.text.InputType
import android.text.TextUtils
import android.view.View
import android.widget.EditText
import android.widget.RadioButton
import android.widget.RadioGroup
import android.widget.TextView
import android.widget.Toast
import android.app.AlertDialog
import android.content.pm.PackageManager
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.os.Environment
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.core.content.FileProvider
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.bottomsheet.BottomSheetDialog
import com.livinglifetechway.quickpermissions_kotlin.runWithPermissions
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_edit_profil.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import org.json.JSONArray
import org.json.JSONObject
import org.w3c.dom.Text
import java.io.File
import java.io.IOException
import java.text.SimpleDateFormat
import java.util.*

class EditProfilActivity:AppCompatActivity(), View.OnClickListener, LocationListener {
    lateinit var mediaHelper: MediaHelper
    private lateinit var alertDialog: AlertDialog
    var imStr = ""
    var namaFoto = ""
    val user = "http://192.168.43.57/simaptapkl/public/service/akun.php"
    val timPeserta = "http://192.168.43.57/simaptapkl/public/service/timPeserta.php"
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    var formkelamin = ""
    var cekKoneksi = "1"
    var formagama = ""
    var lihat = "0"
    var gantiFoto = "0"
    var fileUri = Uri.parse("")
    lateinit var dialog: BottomSheetDialog
    private lateinit var locationManager: LocationManager
    lateinit var isDialog: AlertDialog
    var proses = ""
    val daftarForum = mutableListOf<HashMap<String,String>>()
    lateinit var forumTimPeserta: AdapterTimPeserta
    var currentPhotoPath = ""
    var cekGPS = "1"
    lateinit var isInternet: AlertDialog
    lateinit var isGPS: AlertDialog
    lateinit var isInternetGPS: AlertDialog
    private var isActivityRunning = false

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.imageView14->{
                if(lihat.equals("1")) {
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

                    window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
                    window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
                }else if(lihat.equals("2")){
                    lihat = "0"
                    textView68.setText("Profil User")
                    imageView18.visibility = View.VISIBLE
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

                    window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
                    window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
                }else{
                    proses = "1"
                    finish()
                    val transitions = Transitions(this)
                    transitions.setAnimation(Fade().InLeft())
                    locationManager.removeUpdates(this)
                }
            }
            R.id.imageView18->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_edit_profil, null)
                val hapus = dialogView.findViewById<CircleImageView>(R.id.hapus)
                val storage = dialogView.findViewById<CircleImageView>(R.id.vector)
                val kamera = dialogView.findViewById<CircleImageView>(R.id.camear)

                storage.setOnClickListener {
                    val intent = Intent()
                    intent.setType("image/*")
                    intent.setAction(Intent.ACTION_GET_CONTENT)
                    startActivityForResult(intent,mediaHelper.RcGallery())

                    dialog.dismiss()
                }

                kamera.setOnClickListener {
                    dispatchTakePictureIntent()
                    dialog.dismiss()
                }

                hapus.setOnClickListener {
                    dialog.dismiss()
                    namaFoto = "profil.png"
                    val inflater = layoutInflater
                    val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                    val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                    val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                    val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                    kembali.setText("Kembali")
                    kirim.setText("Hapus")
                    informasi.setText("Apakah anda ingin menghapus foto ini dan kembali ke foto awal ?")

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
                                alertDialog.dismiss()
                                gantiFoto = "1"
                                Picasso.get().load("http://192.168.43.57/simaptapkl/public/service/profil/"+namaFoto).into(profile_image)
                                Picasso.get().load("http://192.168.43.57/simaptapkl/public/service/profil/"+namaFoto).into(imageView16)
                                hapusFoto()
                                loading.isDismiss()
                            }
                        },3000)
                    }

                    val builder = android.app.AlertDialog.Builder(this@EditProfilActivity)
                    builder.setView(dialogView)
                    alertDialog = builder.create()
                    alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                    alertDialog.show()
                }

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }

            R.id.kelamin->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_form_kelamin, null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView52)
                val kembali = dialogView.findViewById<TextView>(R.id.textView54)
                val simpan = dialogView.findViewById<TextView>(R.id.textView55)
                val group = dialogView.findViewById<RadioGroup>(R.id.radioGroup)
                val laki = dialogView.findViewById<RadioButton>(R.id.radioButton)
                val perem = dialogView.findViewById<RadioButton>(R.id.radioButton2)

                informasi.setText("Pilih Kelamin : ")
                kembali.setOnClickListener {
                    dialog.dismiss()
                }

                if(textView105.text.toString().equals("Laki-laki")){
                    laki.isChecked = true
                }else{
                    perem.isChecked = true
                }

                group.setOnCheckedChangeListener { group, checkedId ->
                    when(checkedId){
                        R.id.radioButton -> formkelamin = "Laki-laki"
                        R.id.radioButton2 -> formkelamin = "Perempuan"
                    }
                }

                simpan.setOnClickListener {
                    editProfil(textView82.text.toString(),formkelamin,textView108.text.toString(),textView110.text.toString(),textView112.text.toString(),textView120.text.toString(),textView122.text.toString())
                    dialog.dismiss()
                    textView105.setText(formkelamin)
                }

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }
            R.id.handphone->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_form_edit, null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView52)
                val kembali = dialogView.findViewById<TextView>(R.id.textView54)
                val simpan = dialogView.findViewById<TextView>(R.id.textView55)
                val formInput = dialogView.findViewById<EditText>(R.id.editTextTextPersonName)
                formInput.inputType = InputType.TYPE_CLASS_NUMBER

                formInput.setText(textView108.text.toString())
                informasi.setText("Masukkan No Whatshapp : ")
                kembali.setOnClickListener {
                    dialog.dismiss()
                }

                simpan.setOnClickListener {
                    if(TextUtils.isEmpty(formInput.text)){
                        Toast.makeText(this, "Data kosong", Toast.LENGTH_SHORT).show()
                    }else{
                        editProfil(textView82.text.toString(),textView105.text.toString(),formInput.text.toString(),textView110.text.toString(),textView112.text.toString(),textView120.text.toString(),textView122.text.toString())
                        dialog.dismiss()
                        textView108.setText(formInput.text.toString())
                    }
                }

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }
            R.id.agama->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_form_agama, null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView52)
                val kembali = dialogView.findViewById<TextView>(R.id.textView54)
                val simpan = dialogView.findViewById<TextView>(R.id.textView55)
                val group2 = dialogView.findViewById<RadioGroup>(R.id.radioGroup2)
                val isl = dialogView.findViewById<RadioButton>(R.id.radioButton)
                val kris = dialogView.findViewById<RadioButton>(R.id.radioButton2)
                val hin = dialogView.findViewById<RadioButton>(R.id.radioButton3)
                val bud = dialogView.findViewById<RadioButton>(R.id.radioButton4)
                val kong = dialogView.findViewById<RadioButton>(R.id.radioButton5)

                informasi.setText("Pilih Agama : ")
                kembali.setOnClickListener {
                    dialog.dismiss()
                }

                if(textView110.text.toString().equals("Islam")){
                    isl.isChecked = true
                }else if(textView110.text.toString().equals("Kristen")){
                    kris.isChecked = true
                }else if(textView110.text.toString().equals("Hindu")){
                    hin.isChecked = true
                }else if(textView110.text.toString().equals("Budha")){
                    bud.isChecked = true
                }else if(textView110.text.toString().equals("Konghucu")){
                    kong.isChecked = true
                }

                group2.setOnCheckedChangeListener { group, checkedId ->
                    when(checkedId){
                        R.id.radioButton -> formagama = "Islam"
                        R.id.radioButton2 -> formagama = "Kristen"
                        R.id.radioButton3 -> formagama = "Hindu"
                        R.id.radioButton4 -> formagama = "Budha"
                        R.id.radioButton5 -> formagama = "Konghucu"
                    }
                }

                simpan.setOnClickListener {
                    editProfil(textView82.text.toString(),textView105.text.toString(),textView108.text.toString(),formagama,textView112.text.toString(),textView120.text.toString(),textView122.text.toString())
                    dialog.dismiss()
                    textView110.setText(formagama)
                }

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }
            R.id.alamat->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_form_edit, null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView52)
                val kembali = dialogView.findViewById<TextView>(R.id.textView54)
                val simpan = dialogView.findViewById<TextView>(R.id.textView55)
                val formInput = dialogView.findViewById<EditText>(R.id.editTextTextPersonName)
                formInput.inputType = InputType.TYPE_CLASS_TEXT or InputType.TYPE_TEXT_FLAG_MULTI_LINE

                formInput.setText(textView112.text.toString())
                informasi.setText("Masukkan Alamat : ")
                kembali.setOnClickListener {
                    dialog.dismiss()
                }

                simpan.setOnClickListener {
                    if(TextUtils.isEmpty(formInput.text)){
                        Toast.makeText(this, "Data kosong", Toast.LENGTH_SHORT).show()
                    }else{
                        editProfil(textView82.text.toString(),textView105.text.toString(),textView108.text.toString(),textView110.text.toString(),formInput.text.toString(),textView120.text.toString(),textView122.text.toString())
                        dialog.dismiss()
                        textView112.setText(formInput.text.toString())
                    }
                }

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }
            R.id.username->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_form_edit, null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView52)
                val kembali = dialogView.findViewById<TextView>(R.id.textView54)
                val simpan = dialogView.findViewById<TextView>(R.id.textView55)
                val formInput = dialogView.findViewById<EditText>(R.id.editTextTextPersonName)
                formInput.inputType = InputType.TYPE_TEXT_VARIATION_EMAIL_ADDRESS

                formInput.setText(textView120.text.toString())
                informasi.setText("Masukkan Username : ")
                kembali.setOnClickListener {
                    dialog.dismiss()
                }

                simpan.setOnClickListener {
                    if(TextUtils.isEmpty(formInput.text)){
                        Toast.makeText(this, "Data kosong", Toast.LENGTH_SHORT).show()
                    }else{
                        editProfil(textView82.text.toString(),textView105.text.toString(),textView108.text.toString(),textView110.text.toString(),textView112.text.toString(),formInput.text.toString(),textView122.text.toString())
                        dialog.dismiss()
                        textView120.setText(formInput.text.toString())
                    }
                }

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }
            R.id.password->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_form_edit, null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView52)
                val kembali = dialogView.findViewById<TextView>(R.id.textView54)
                val simpan = dialogView.findViewById<TextView>(R.id.textView55)
                val formInput = dialogView.findViewById<EditText>(R.id.editTextTextPersonName)

                formInput.setText(textView122.text.toString())
                informasi.setText("Masukkan Password : ")
                kembali.setOnClickListener {
                    dialog.dismiss()
                }

                simpan.setOnClickListener {
                    if(TextUtils.isEmpty(formInput.text)){
                        Toast.makeText(this, "Data kosong", Toast.LENGTH_SHORT).show()
                    }else{
                        editProfil(textView82.text.toString(),textView105.text.toString(),textView108.text.toString(),textView110.text.toString(),textView112.text.toString(),textView120.text.toString(),formInput.text.toString())
                        dialog.dismiss()
                        textView122.setText(formInput.text.toString())
                    }
                }

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }
            R.id.namaAsal->{
                val inflater = layoutInflater
                val dialogView = inflater.inflate(R.layout.informasi_asal,null)
                val kembali = dialogView.findViewById<TextView>(R.id.informasiKembali)
                val nama = dialogView.findViewById<TextView>(R.id.textView82)
                val telp = dialogView.findViewById<TextView>(R.id.textView108)
                val fax = dialogView.findViewById<TextView>(R.id.textView110)
                val alamat = dialogView.findViewById<TextView>(R.id.textView112)
                val website = dialogView.findViewById<TextView>(R.id.textView120)

                nama.setText(textView138.text.toString())
                telp.setText(textView140.text.toString())
                fax.setText(textView141.text.toString())
                alamat.setText(textView139.text.toString())
                website.setText(textView142.text.toString())
                kembali.setText("Kembali")

                kembali.setOnClickListener {
                    isDialog.dismiss()
                }
                website.setOnClickListener {
                    isDialog.dismiss()
                    startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            isDismiss()
                            val url = textView142.text.toString() // Replace with the URL of the website you want to open
                            val intent = Intent(Intent.ACTION_VIEW, Uri.parse(url))
                            startActivity(intent)
                        }
                    },3000)
                }

                val builder = android.app.AlertDialog.Builder(this@EditProfilActivity)
                builder.setView(dialogView)
                isDialog = builder.create()
                isDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                isDialog.show()
            }
            R.id.namaTim->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_tm_pst, null)
                val recycler = dialogView.findViewById<RecyclerView>(R.id.lsForumBottomSheetDialog)

                recycler.layoutManager = LinearLayoutManager(this)
                recycler.adapter = forumTimPeserta
                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }
            R.id.pembimbing->{
                val inflater = layoutInflater
                val dialogView = inflater.inflate(R.layout.informasi_person,null)
                val kembali = dialogView.findViewById<TextView>(R.id.informasiKembali)
                val nama = dialogView.findViewById<TextView>(R.id.textView82)
                val kelamin = dialogView.findViewById<TextView>(R.id.textView105)
                val agama = dialogView.findViewById<TextView>(R.id.textView110)
                val alamat = dialogView.findViewById<TextView>(R.id.textView112)
                val whatshap = dialogView.findViewById<TextView>(R.id.textView108)
                val email = dialogView.findViewById<TextView>(R.id.textView120)

                nama.setText(textView131.text.toString())
                kelamin.setText(textView132.text.toString())
                agama.setText(textView133.text.toString())
                alamat.setText(textView134.text.toString())
                whatshap.setText(textView135.text.toString())
                email.setText(textView136.text.toString())
                kembali.setText("Kembali")

                kembali.setOnClickListener {
                    isDialog.dismiss()
                }

                email.setOnClickListener {
                    isDialog.dismiss()
                    startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            isDismiss()
                            val intent = Intent(Intent.ACTION_SENDTO)
                            intent.data = Uri.parse("mailto:") // hanya aplikasi email yang akan menangani intent ini
                            intent.putExtra(Intent.EXTRA_EMAIL, arrayOf(textView136.text.toString())) // tambahkan alamat email penerima
                            intent.putExtra(Intent.EXTRA_SUBJECT, "Subject of the email") // tambahkan subjek email
                            if (intent.resolveActivity(packageManager) != null) {
                                startActivity(intent)
                            } else {
                                Toast.makeText(this@EditProfilActivity, "No email app found", Toast.LENGTH_SHORT).show()
                            }
                        }
                    },3000)
                }

                whatshap.setOnClickListener {
                    isDialog.dismiss()
                    startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            isDismiss()
                            val packageManager = packageManager
                            val whatsappIntent = Intent(Intent.ACTION_SENDTO, Uri.parse("smsto:" + textView135.text.toString() + "@s.whatsapp.net"))
                            whatsappIntent.setPackage("com.whatsapp")
                            if (whatsappIntent.resolveActivity(packageManager) != null) {
                                startActivity(whatsappIntent)
                            } else {
                                Toast.makeText(this@EditProfilActivity, "WhatsApp not installed", Toast.LENGTH_SHORT).show()
                            }
                        }
                    },3000)
                }

                val builder = android.app.AlertDialog.Builder(this@EditProfilActivity)
                builder.setView(dialogView)
                isDialog = builder.create()
                isDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                isDialog.show()
            }
            R.id.btnEdit->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_edit_profil, null)
                val hapus = dialogView.findViewById<CircleImageView>(R.id.hapus)
                val storage = dialogView.findViewById<CircleImageView>(R.id.vector)
                val kamera = dialogView.findViewById<CircleImageView>(R.id.camear)

                storage.setOnClickListener {
                    val intent = Intent()
                    intent.setType("image/*")
                    intent.setAction(Intent.ACTION_GET_CONTENT)
                    startActivityForResult(intent,mediaHelper.RcGallery())

                    dialog.dismiss()
                }

                kamera.setOnClickListener {
                    dispatchTakePictureIntent()
                    dialog.dismiss()
                }

                hapus.setOnClickListener {
                    dialog.dismiss()
                    namaFoto = "profil.png"
                    val inflater = layoutInflater
                    val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                    val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                    val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                    val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                    kembali.setText("Kembali")
                    kirim.setText("Hapus")
                    informasi.setText("Apakah anda ingin menghapus foto ini dan kembali ke foto awal ?")

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
                                alertDialog.dismiss()
                                gantiFoto = "1"
                                Picasso.get().load("http://192.168.43.57/simaptapkl/public/service/profil/"+namaFoto).into(profile_image)
                                Picasso.get().load("http://192.168.43.57/simaptapkl/public/service/profil/"+namaFoto).into(imageView16)
                                hapusFoto()
                                loading.isDismiss()
                            }
                        },3000)
                    }

                    val builder = android.app.AlertDialog.Builder(this@EditProfilActivity)
                    builder.setView(dialogView)
                    alertDialog = builder.create()
                    alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                    alertDialog.show()
                }

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
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

                window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
                window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
            }
            R.id.profile_image->{
                if(gantiFoto.equals("1")){
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
                    window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_VISIBLE

                    flFoto.visibility = View.VISIBLE
                    animatorSet.start()
                }else{
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
                    window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_VISIBLE

                    flFoto.visibility = View.VISIBLE
                    animatorSet.start()
                }
            }
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_edit_profil)

        supportActionBar?.hide()
        window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager
        mediaHelper = MediaHelper(this)
        cr.setOnClickListener(this)
        imageView17.setOnClickListener(this)
        imageView18.setOnClickListener(this)

        imageView14.setOnClickListener(this)
        btnEdit.setOnClickListener(this)
        profile_image.setOnClickListener(this)
        namaPeserta.setOnClickListener(this)
        kelamin.setOnClickListener(this)
        handphone.setOnClickListener(this)
        agama.setOnClickListener(this)
        alamat.setOnClickListener(this)
        username.setOnClickListener(this)
        password.setOnClickListener(this)
        namaAsal.setOnClickListener(this)
        namaTim.setOnClickListener(this)
        pembimbing.setOnClickListener(this)

        forumTimPeserta = AdapterTimPeserta(daftarForum, this)
        daftarTimPeserta()

        akun()
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

    override fun onBackPressed() {
        if(lihat.equals("1")) {
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

            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
            window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        }else if(lihat.equals("2")){
            lihat = "0"
            textView68.setText("Profil User")
            imageView18.visibility = View.VISIBLE
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

            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColorWhite)
            window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        }else{
            proses = "1"
            super.onBackPressed()
            val transitions = Transitions(this)
            transitions.setAnimation(Fade().InLeft())
            locationManager.removeUpdates(this)
        }
    }

    override fun finish() {
        var intent = Intent()

        if(!proses.equals("")){
            intent.putExtra("proses", proses)
            setResult(Activity.RESULT_OK,intent)
        }

        super.finish()
    }

    private fun dispatchTakePictureIntent() {
        val takePictureIntent = Intent(MediaStore.ACTION_IMAGE_CAPTURE)
        if (takePictureIntent.resolveActivity(packageManager) != null) {
            val photoFile: File? = try {
                createImageFile()
            } catch (ex: IOException) {
                null
            }

            photoFile?.also {
                val photoURI: Uri = FileProvider.getUriForFile(
                    this,
                    "com.example.android.treasureHunt.provider",
                    it
                )
                takePictureIntent.putExtra(MediaStore.EXTRA_OUTPUT, photoURI)
                startActivityForResult(takePictureIntent, mediaHelper.getRcCamera())
            }
        }
    }

    private fun createImageFile(): File {
        val timeStamp: String = SimpleDateFormat("yyyyMMdd_HHmmss").format(Date())
        val storageDir: File? = getExternalFilesDir(Environment.DIRECTORY_PICTURES)
        return File.createTempFile(
            "JPEG_${timeStamp}_",
            ".jpg",
            storageDir
        ).apply {
            currentPhotoPath = absolutePath
        }
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)
        if(resultCode == Activity.RESULT_OK){
            if(requestCode == mediaHelper.RcGallery()){
                val loading = LoadingDialog(this)
                loading.startLoading()
                val handler = Handler()
                handler.postDelayed(object:Runnable{
                    override fun run() {
                        imStr = mediaHelper.getBitmapToString(data!!.data,profile_image)
                        imStr = mediaHelper.getBitmapToString(data!!.data,imageView16)
                        val nmFile ="dc" + SimpleDateFormat("yyyyddMMHHmmss", Locale.getDefault()).format(
                            Date()
                        )+".jpg"
                        namaFoto = nmFile
                        gantiFoto = "1"
                        editProfil(textView82.text.toString(),textView105.text.toString(),textView108.text.toString(),textView110.text.toString(),textView112.text.toString(),textView120.text.toString(),textView122.text.toString())
                        loading.isDismiss()
                    }
                },3000)
            }else if(requestCode == mediaHelper.getRcCamera()){
                val loading = LoadingDialog(this)
                loading.startLoading()
                val handler = Handler()
                handler.postDelayed(object:Runnable{
                    override fun run() {
                        imStr = mediaHelper.getBitmapToString(profile_image,currentPhotoPath)
                        imStr = mediaHelper.getBitmapToString(imageView16,currentPhotoPath)
                        namaFoto ="dc" + SimpleDateFormat("yyyyddMMHHmmss", Locale.getDefault()).format(
                            Date()
                        )+".jpg"

                        gantiFoto = "1"
                        editProfil(textView82.text.toString(),textView105.text.toString(),textView108.text.toString(),textView110.text.toString(),textView112.text.toString(),textView120.text.toString(),textView122.text.toString())
                        loading.isDismiss()
                    }
                },3000)
            }
        }
    }

    fun daftarTimPeserta(){
        val request = object : StringRequest(
            Method.POST,timPeserta,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("kd_mhs",jsonObject.getString("KD_PST"))
                    frm.put("nama_mhs",jsonObject.getString("NAMA_PST"))
                    frm.put("kelamin",jsonObject.getString("JK_PST"))
                    frm.put("agama",jsonObject.getString("AGAMA_PST"))
                    frm.put("alamat",jsonObject.getString("ALAMAT_PST"))
                    frm.put("whatshap",jsonObject.getString("NOHP_PST"))
                    frm.put("email",jsonObject.getString("EMAIL"))
                    frm.put("fotoProfil",jsonObject.getString("url"))

                    daftarForum.add(frm)
                }
                forumTimPeserta.notifyDataSetChanged()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","view")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun hapusFoto(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){

                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","hapusFoto")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun editProfil(mahasiswa:String,kelamin:String,handphone:String,agama:String,alamat:String,username:String,password:String,){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){

                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","edit")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("mahasiswa",mahasiswa)
                data.put("kelamin",kelamin)
                data.put("handphone",handphone)
                data.put("agama",agama)
                data.put("alamat",alamat)
                data.put("email",username)
                data.put("password",password)
                data.put("image",imStr)
                data.put("foto",namaFoto)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun akun(){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val mahasiswa = jsonObject.getString("NAMA_PST")
                val kampus = jsonObject.getString("NAMA_ASAL")
                val kelamin = jsonObject.getString("JK_PST")
                val handphone = jsonObject.getString("NOHP_PST")
                val agama = jsonObject.getString("AGAMA_PST")
                val alamat = jsonObject.getString("ALAMAT_PST")
                val mulai = jsonObject.getString("masuk")
                val selesai = jsonObject.getString("keluar")
                val karyawan = jsonObject.getString("NAMA_KAWAN")
                val ema = jsonObject.getString("EMAIL")
                val pass = jsonObject.getString("PASSWORD")
                val fotoProfil = jsonObject.getString("url")
                val namaTim = jsonObject.getString("NAMA_TIM")
                val kodeKawan = jsonObject.getString("KD_KAWAN")
                val kodeAsal = jsonObject.getString("KD_ASAL")

                textView82.setText(mahasiswa)
                textView84.setText(kampus)
                textView108.setText(handphone)
                textView110.setText(agama)
                formagama = agama
                textView112.setText(alamat)
                textView103.setText(namaTim)
                textView105.setText(kelamin)
                formkelamin = kelamin
                textView114.setText(mulai)
                textView116.setText(selesai)
                textView118.setText(karyawan)
                textView120.setText(ema)
                textView122.setText(pass)
                Picasso.get().load(fotoProfil).into(profile_image)
                informasiPembimbing(kodeKawan)
                informasiAsal(kodeAsal)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","view")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun informasiPembimbing(kodeKawan: String){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val karyawan = jsonObject.getString("NAMA_KAWAN")
                val kelamin = jsonObject.getString("JK_KAWAN")
                val agama = jsonObject.getString("AGAMA_KAWAN")
                val alamat = jsonObject.getString("ALAMAT_KAWAN")
                val whatshap = jsonObject.getString("NOHP_KAWAN")
                val email = jsonObject.getString("EMAIL")
                val profil = jsonObject.getString("url")

                textView131.setText(karyawan)
                textView132.setText(kelamin)
                textView133.setText(agama)
                textView134.setText(alamat)
                textView135.setText(whatshap)
                textView136.setText(email)
                textView137.setText(profil)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","viewInformasiPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("kd_kawan", kodeKawan)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun informasiAsal(kodeAsal: String){
        val request = object : StringRequest(
            Method.POST,user,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val nama = jsonObject.getString("NAMA_ASAL")
                val alamat = jsonObject.getString("ALAMAT_ASAL")
                val tlp = jsonObject.getString("TELP_ASAL")
                val fax = jsonObject.getString("FAX_ASAL")
                val website = jsonObject.getString("WEBSITE_ASAL")

                textView138.setText(nama)
                textView139.setText(alamat)
                textView140.setText(tlp)
                textView141.setText(fax)
                textView142.setText(website)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","viewInformasiAsal")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("kd_kmps", kodeAsal)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
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
                data.put("pilihan","view")
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