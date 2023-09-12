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

class EditProfilPembimbingActivity:AppCompatActivity(), View.OnClickListener {
    lateinit var isDialog: AlertDialog
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    val user = "http://192.168.43.57/simaptapkl/public/service/akun.php"
    var formkelamin = ""
    var formagama = ""
    var lihat = ""
    var gantiFoto = ""
    lateinit var mediaHelper: MediaHelper
    var imStr = ""
    var namaFoto = ""
    var fileUri = Uri.parse("")
    lateinit var dialog: BottomSheetDialog
    private lateinit var alertDialog: AlertDialog
    var cekKoneksi = "1"
    var currentPhotoPath = ""

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.imageView14->{
                finish()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
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

                    val builder = android.app.AlertDialog.Builder(this@EditProfilPembimbingActivity)
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

                    val builder = android.app.AlertDialog.Builder(this@EditProfilPembimbingActivity)
                    builder.setView(dialogView)
                    alertDialog = builder.create()
                    alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                    alertDialog.show()
                }

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }
            R.id.namaPeserta->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_form_edit, null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView52)
                val kembali = dialogView.findViewById<TextView>(R.id.textView54)
                val simpan = dialogView.findViewById<TextView>(R.id.textView55)
                val formInput = dialogView.findViewById<EditText>(R.id.editTextTextPersonName)

                formInput.setText(textView82.text.toString())
                informasi.setText("Masukkan Nama Pembimbing : ")
                kembali.setOnClickListener {
                    dialog.dismiss()
                }

                simpan.setOnClickListener {
                    if(TextUtils.isEmpty(formInput.text)){
                        Toast.makeText(this, "Data kosong", Toast.LENGTH_SHORT).show()
                    }else{
                        editProfil(formInput.text.toString(),textView105.text.toString(),textView108.text.toString(),textView110.text.toString(),textView112.text.toString(),textView120.text.toString(),textView122.text.toString())
                        dialog.dismiss()
                        textView82.setText(formInput.text.toString())
                    }
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
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_edit_profil_pembimbing)

        supportActionBar?.hide()
        window.decorView.systemUiVisibility = window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
        mediaHelper = MediaHelper(this)
        cr.setOnClickListener(this)
        imageView14.setOnClickListener(this)
        btnEdit.setOnClickListener(this)
        profile_image.setOnClickListener(this)
        imageView17.setOnClickListener(this)
        imageView18.setOnClickListener(this)
        namaPeserta.setOnClickListener(this)
        kelamin.setOnClickListener(this)
        handphone.setOnClickListener(this)
        agama.setOnClickListener(this)
        alamat.setOnClickListener(this)
        username.setOnClickListener(this)
        password.setOnClickListener(this)

        akun()

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
        }else{
            super.onBackPressed()
            val transitions = Transitions(this)
            transitions.setAnimation(Fade().InLeft())
        }
    }

    fun editProfil(karyawan:String,kelamin:String,handphone:String,agama:String,alamat:String,username:String,password:String,){
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
                data.put("pilihan","editPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("karyawan",karyawan)
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
                data.put("pilihan","hapusFotoPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

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
                data.put("pilihan","viewPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

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
                val karyawan = jsonObject.getString("NAMA_KAWAN")
                val kelamin = jsonObject.getString("JK_KAWAN")
                val handphone = jsonObject.getString("NOHP_KAWAN")
                val agama = jsonObject.getString("AGAMA_KAWAN")
                val alamat = jsonObject.getString("ALAMAT_KAWAN")
                val jabatan = jsonObject.getString("NAMA_JBTN")
                val ema = jsonObject.getString("EMAIL")
                val pass = jsonObject.getString("PASSWORD")
                val fotoProfil = jsonObject.getString("url")

                textView82.setText(karyawan)
                formkelamin = kelamin
                textView105.setText(kelamin)
                textView108.setText(handphone)
                textView110.setText(agama)
                formagama = agama
                textView112.setText(alamat)
                textView118.setText(jabatan)
                textView120.setText(ema)
                textView122.setText(pass)
                Picasso.get().load(fotoProfil).into(profile_image)
                Picasso.get().load(fotoProfil).into(imageView16)
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","viewPembimbing")
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