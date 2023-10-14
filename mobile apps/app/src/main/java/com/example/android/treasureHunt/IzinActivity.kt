package com.example.android.treasureHunt

import android.Manifest
import android.animation.Animator
import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.app.Activity
import android.app.AlertDialog
import android.content.Context
import android.content.DialogInterface
import android.content.Intent
import android.content.SharedPreferences
import android.content.pm.PackageManager
import android.graphics.Color
import android.graphics.drawable.ColorDrawable
import android.location.Geocoder
import android.location.Location
import android.location.LocationListener
import android.location.LocationManager
import android.net.Uri
import android.os.Bundle
import android.os.Environment
import android.os.Handler
import android.provider.MediaStore
import android.speech.RecognizerIntent
import android.view.LayoutInflater
import android.view.Menu
import android.view.MenuItem
import android.view.View
import android.widget.TextView
import androidx.appcompat.widget.SearchView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.core.content.FileProvider
import androidx.recyclerview.widget.LinearLayoutManager
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import com.google.android.material.bottomsheet.BottomSheetDialog
import com.google.android.material.textfield.TextInputEditText
import com.livinglifetechway.quickpermissions_kotlin.runWithPermissions
import com.loopj.android.http.AsyncHttpClient
import com.loopj.android.http.AsyncHttpResponseHandler
import com.loopj.android.http.RequestParams
import com.squareup.picasso.Picasso
import cz.msebera.android.httpclient.Header
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_edit_profil.*
import kotlinx.android.synthetic.main.activity_izin.*
import kotlinx.android.synthetic.main.activity_izin.imageView16
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import mumayank.com.airlocationlibrary.AirLocation
import org.json.JSONArray
import org.json.JSONObject
import java.io.File
import java.io.IOException
import java.text.SimpleDateFormat
import java.util.*
import kotlin.collections.HashMap

class IzinActivity:AppCompatActivity(), View.OnClickListener, LocationListener {
    val user = "http://192.168.43.57/simaptapkl/public/service/akun.php"
    val perizinan = "http://192.168.43.57/simaptapkl/public/service/perizinan.php"
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""
    lateinit var forumIzin : AdapterIzin
    val daftarForum = mutableListOf<HashMap<String,String>>()
    lateinit var mediaHelper: MediaHelper
    var imStr = ""
    var cekKoneksi = "1"
    var airLoc : AirLocation? = null
    var SPEECH_REQUEST_CODE = 30
    lateinit var searchView: SearchView
    lateinit var isDialog: AlertDialog
    lateinit var searchItem: MenuItem
    lateinit var micItem: MenuItem
    var lihat = "0"
    lateinit var dialog: BottomSheetDialog
    var fileUri = Uri.parse("")
    lateinit var alertDialog: AlertDialog
    var jenisEdit = ""
    private lateinit var locationManager: LocationManager
    var proses = ""
    var currentPhotoPath = ""
    var namaFoto = ""
    var cekGPS = "1"
    lateinit var isInternet: AlertDialog
    lateinit var isGPS: AlertDialog
    lateinit var isInternetGPS: AlertDialog
    private var isActivityRunning = false

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.tmbhIzin->{
                val dialogView = layoutInflater.inflate(R.layout.bottom_sheet_surat_izin, null)
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

                dialog = BottomSheetDialog(this, R.style.BottomSheetDialogTheme)
                dialog.setContentView(dialogView)
                dialog.show()
            }
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_izin)

        tmbhIzin.setOnClickListener(this)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.setTitle("Perizinan")
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
        locationManager = getSystemService(Context.LOCATION_SERVICE) as LocationManager

        forumIzin = AdapterIzin(daftarForum, this)
        lsForum.layoutManager = LinearLayoutManager(this)
        lsForum.adapter = forumIzin

        mediaHelper = MediaHelper(this)

        var paket : Bundle? = intent.extras
        if(paket?.getString("status").equals("tidak aktif")){
            tmbhIzin.isEnabled = false
        }else{
            cekIzin()
        }

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

        akun()
        dataIzin()
        jumlahIzinWaiting()
        enableUserLocation()
        refreshUp()
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

    private fun refreshUp(){
        refreshIzin.setColorSchemeColors(
            Color.parseColor("#5AA0FF")
        )
        refreshIzin.setOnRefreshListener {
            Handler().postDelayed(Runnable {
                dataIzin()
                jumlahIzinWaiting()
                cekIzin()
                refreshIzin.isRefreshing = false
            }, 2000)
        }
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

    override fun onCreateOptionsMenu(menu: Menu?): Boolean {
        menuInflater.inflate(R.menu.menu_aksi_izin, menu)
        searchItem = menu?.findItem(R.id.searchItem)!!
        micItem = menu?.findItem(R.id.micItem)!!
        searchView = searchItem?.actionView as SearchView

        searchView.queryHint = "Search Here ..."
        searchView.setOnQueryTextListener(object : SearchView.OnQueryTextListener {
            override fun onQueryTextSubmit(query: String?): Boolean {
                return false
            }

            override fun onQueryTextChange(newText: String?): Boolean {
                forumIzin.filter.filter(newText)
                return true
            }
        })

        return super.onCreateOptionsMenu(menu)
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when(item?.itemId){
            R.id.micItem->{
                val intent = Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH).apply {
                    putExtra(
                        RecognizerIntent.EXTRA_LANGUAGE_MODEL,
                        RecognizerIntent.LANGUAGE_MODEL_FREE_FORM
                    )
                }
                startActivityForResult(intent, SPEECH_REQUEST_CODE)
            }
        }
        return super.onOptionsItemSelected(item)
    }

    override fun onSupportNavigateUp(): Boolean {
        if(lihat.equals("1")){
            val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    imageView16.scaleX = value
                    imageView16.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(flSurat, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        flSurat.visibility = View.GONE
                    }
                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }
            animatorSet.start()

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Perizinan")

            supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(ContextCompat.getColor(this@IzinActivity, R.color.statusBarColor)))
            }
            searchItem.isVisible = true
            micItem.isVisible = true

            lihat = "0"
        }else{
            if (isTaskRoot) {
                var paket : Bundle? = intent.extras
                var notifikasi = paket?.getString("notifikasi").toString()

                val intent = Intent(this, HuntMainActivity::class.java)
                intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_SINGLE_TOP
                intent.putExtra("notifikasi", notifikasi)
                startActivity(intent)
                finish()

                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
            }else{
                proses = "1"
                finish()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
                locationManager.removeUpdates(this)
            }
        }

        return true
    }

    override fun onBackPressed() {
        if(lihat.equals("1")){
            val animator = ValueAnimator.ofFloat(1f, 0f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    imageView16.scaleX = value
                    imageView16.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(flSurat, "alpha", 1f, 0f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
                addListener(object : Animator.AnimatorListener {
                    override fun onAnimationStart(animation: Animator?) {}
                    override fun onAnimationEnd(animation: Animator?) {
                        flSurat.visibility = View.GONE
                    }
                    override fun onAnimationCancel(animation: Animator?) {}
                    override fun onAnimationRepeat(animation: Animator?) {}
                })
            }
            animatorSet.start()

            supportActionBar?.show()
            window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)
            supportActionBar?.setTitle("Perizinan")

            supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(ContextCompat.getColor(this@IzinActivity, R.color.statusBarColor)))
            }
            searchItem.isVisible = true
            micItem.isVisible = true

            lihat = "0"
        }else{
            if (isTaskRoot) {
                var paket : Bundle? = intent.extras
                var notifikasi = paket?.getString("notifikasi").toString()

                val intent = Intent(this, HuntMainActivity::class.java)
                intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_SINGLE_TOP
                intent.putExtra("notifikasi", notifikasi)
                startActivity(intent)
                finish()

                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
            }else{
                proses = "1"
                super.onBackPressed()
                val transitions = Transitions(this)
                transitions.setAnimation(Fade().InLeft())
                locationManager.removeUpdates(this)
            }
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

    fun found(){
        lsForum.visibility = View.VISIBLE
        imageView13.visibility = View.GONE
    }
//
    fun notFound(){
        lsForum.visibility = View.GONE
        imageView13.visibility = View.VISIBLE
    }

    fun dispatchTakePictureIntentEdit(kode:Int) {
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
                startActivityForResult(takePictureIntent, kode)
            }
        }
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
        airLoc?.onActivityResult(requestCode, resultCode, data)
        super.onActivityResult(requestCode, resultCode, data)
        if (resultCode == Activity.RESULT_OK) {
            if(requestCode == mediaHelper.RcGallery()) {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val user = preferences.getString(ID_AKUN, DEF_ID_AKUN).toString()
                imStr = mediaHelper.getBitmapToStringIzin(data!!.data)
                val nmFile = user + SimpleDateFormat("yyyyddMMHHmmss", Locale.getDefault()).format(
                    Date()
                ) + ".jpg"

                val inflater = layoutInflater
                val dialogView = inflater.inflate(R.layout.activity_form_izin,null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                val kembali = dialogView.findViewById<TextView>(R.id.textView129)
                val formKegiatan = dialogView.findViewById<TextInputEditText>(R.id.formKegiatan)
                val formSurat = dialogView.findViewById<TextInputEditText>(R.id.formSurat)
                formSurat.setText(nmFile)

                kembali.setText("Kembali")
                kirim.setText("Kirim")
                informasi.setText("Apakah anda ingin melakukan izin ketidakhadiran ?")

                kembali.setOnClickListener{
                    alertDialog.dismiss()
                }

                kirim.setOnClickListener{
                    alertDialog.dismiss()
                    if (formKegiatan.text.toString().equals("") || formSurat.text.toString()
                            .equals("")
                    ) {
                        Toast.makeText(this, "Data izin kosong", Toast.LENGTH_SHORT).show()
                    } else {
                        airLoc = AirLocation(this, true, true,
                            object : AirLocation.Callbacks {
                                override fun onFailed(locationFailedEnum: AirLocation.LocationFailedEnum) {
                                    Toast.makeText(
                                        this@IzinActivity,
                                        "Gagal mendapatkan lokasi saat ini",
                                        Toast.LENGTH_LONG
                                    ).show()
                                }

                                override fun onSuccess(location: Location) {
                                    kirimIzin(
                                        nmFile,
                                        formKegiatan.text.toString(),
                                        getJalan(location.latitude, location.longitude),
                                        getDesa(location.latitude, location.longitude),
                                        getKecamatan(location.latitude, location.longitude),
                                        getKabupaten(location.latitude, location.longitude),
                                        getProvinsi(location.latitude, location.longitude),
                                        getKodepos(location.latitude, location.longitude)
                                    )
                                }
                            })
                    }
                }

                val builder = android.app.AlertDialog.Builder(this@IzinActivity)
                builder.setView(dialogView)
                alertDialog = builder.create()
                alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                alertDialog.show()
            }else if(requestCode == mediaHelper.getRcCamera()){
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val user = preferences.getString(ID_AKUN, DEF_ID_AKUN).toString()
                imStr = mediaHelper.getBitmapToStringIzinKamera(currentPhotoPath)
                namaFoto ="dc" + SimpleDateFormat("yyyyddMMHHmmss", Locale.getDefault()).format(
                    Date()
                )+".jpg"

                val inflater = layoutInflater
                val dialogView = inflater.inflate(R.layout.activity_form_izin,null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                val kembali = dialogView.findViewById<TextView>(R.id.textView129)
                val formKegiatan = dialogView.findViewById<TextInputEditText>(R.id.formKegiatan)
                val formSurat = dialogView.findViewById<TextInputEditText>(R.id.formSurat)
                formSurat.setText(namaFoto)

                kembali.setText("Kembali")
                kirim.setText("Kirim")
                informasi.setText("Apakah anda ingin melakukan izin ketidakhadiran ?")

                kembali.setOnClickListener{
                    alertDialog.dismiss()
                }

                kirim.setOnClickListener{
                    alertDialog.dismiss()
                    if (formKegiatan.text.toString().equals("") || formSurat.text.toString()
                                .equals("")
                        ) {
                            Toast.makeText(this, "Data izin kosong", Toast.LENGTH_SHORT).show()
                        } else {
                            airLoc = AirLocation(this, true, true,
                                object : AirLocation.Callbacks {
                                    override fun onFailed(locationFailedEnum: AirLocation.LocationFailedEnum) {
                                        Toast.makeText(
                                            this@IzinActivity,
                                            "Gagal mendapatkan lokasi saat ini",
                                            Toast.LENGTH_LONG
                                        ).show()
                                    }

                                    override fun onSuccess(location: Location) {
                                        kirimIzin(
                                            namaFoto,
                                            formKegiatan.text.toString(),
                                            getJalan(location.latitude, location.longitude),
                                            getDesa(location.latitude, location.longitude),
                                            getKecamatan(location.latitude, location.longitude),
                                            getKabupaten(location.latitude, location.longitude),
                                            getProvinsi(location.latitude, location.longitude),
                                            getKodepos(location.latitude, location.longitude)
                                        )
                                    }
                                })
                        }
                }

                val builder = android.app.AlertDialog.Builder(this@IzinActivity)
                builder.setView(dialogView)
                alertDialog = builder.create()
                alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                alertDialog.show()
            }else if(requestCode == SPEECH_REQUEST_CODE){
                val spokenText: String? =
                    data?.getStringArrayListExtra(RecognizerIntent.EXTRA_RESULTS).let { results ->
                        results?.get(0)
                    }
                // Do something with spokenText
                searchView.setQuery(spokenText, false)
            }else{
                val kode = requestCode
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val user = preferences.getString(ID_AKUN,DEF_ID_AKUN).toString()

                if(jenisEdit.equals("galeri")){

                    imStr = mediaHelper.getBitmapToStringIzin(data!!.data)
                    val nmFile =user + SimpleDateFormat("yyyyddMMHHmmss", Locale.getDefault()).format(
                        Date()
                    )+".jpg"
                    formEdit(kode.toString(), nmFile)
                    jenisEdit = "kosong"
                }else if(jenisEdit.equals("kamera")){
                    imStr = mediaHelper.getBitmapToStringIzinKamera(currentPhotoPath)
                    namaFoto ="dc" + SimpleDateFormat("yyyyddMMHHmmss", Locale.getDefault()).format(
                        Date()
                    )+".jpg"

                    formEdit(kode.toString(), namaFoto)
                    jenisEdit = "kosong"
                }
            }
        }

        super.onActivityResult(requestCode, resultCode, data)
    }

    fun jumlahIzinWaiting(){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val jumlah = jsonObject.getString("jumlah")

                if(jumlah.equals("0")){
                    lsForum.visibility = View.GONE
                    imageView13.visibility = View.VISIBLE
                }else{
                    lsForum.visibility = View.VISIBLE
                    imageView13.visibility = View.GONE
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","jumlahIzin")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<out String>,
        grantResults: IntArray
    ) {
        airLoc?.onRequestPermissionsResult(requestCode, permissions, grantResults)
        super.onRequestPermissionsResult(requestCode, permissions, grantResults)
    }

    fun kirimIzin(surat:String, keterangan:String, jln:String, ds:String, kec:String, kab:String, prov:String, kp:String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            cekIzin()
                            dataIzin()
                            jumlahIzinWaiting()
                            kirimNotifikasi(surat)
                            Toast.makeText(this@IzinActivity,"Surat berhasil terkirim",Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","kirim")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("image",imStr)
                data.put("surat",surat)
                data.put("keterangan",keterangan)
                data.put("jalan",jln)
                data.put("desa",ds)
                data.put("kecamatan",kec)
                data.put("kabupaten",kab)
                data.put("provinsi",prov)
                data.put("kodepos",kp)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun formEdit(kode:String, namaSurat:String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val keterangan = jsonObject.getString("KETERANGAN")

                val inflater = layoutInflater
                val dialogView = inflater.inflate(R.layout.activity_form_izin,null)
                val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                val kembali = dialogView.findViewById<TextView>(R.id.textView129)
                val formKegiatan = dialogView.findViewById<TextInputEditText>(R.id.formKegiatan)
                formKegiatan.setText(keterangan)
                val formSurat = dialogView.findViewById<TextInputEditText>(R.id.formSurat)
                formSurat.setText(namaSurat)

                kembali.setText("Kembali")
                kirim.setText("Kirim")
                informasi.setText("Apakah anda ingin mengedit surat izin ketidakhadiran ?")

                kembali.setOnClickListener{
                    alertDialog.dismiss()
                }

                kirim.setOnClickListener{
                    alertDialog.dismiss()
                    if(formKegiatan.text.toString().equals("") || formSurat.text.toString().equals("")){
                        Toast.makeText(this, "Data izin kosong", Toast.LENGTH_SHORT).show()
                    }else {
                        airLoc = AirLocation(this,true,true,
                            object : AirLocation.Callbacks{
                                override fun onFailed(locationFailedEnum: AirLocation.LocationFailedEnum) {
                                    Toast.makeText(this@IzinActivity,"Gagal mendapatkan lokasi saat ini", Toast.LENGTH_LONG).show()
                                }

                                override fun onSuccess(location: Location) {
                                    editKirimIzin(namaSurat, formKegiatan.text.toString(), kode)
                                }
                            })
                    }
                }

                val builder = android.app.AlertDialog.Builder(this@IzinActivity)
                builder.setView(dialogView)
                alertDialog = builder.create()
                alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                alertDialog.show()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","formEdit")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("kode",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun editKirimIzin(surat:String, keterangan:String, kode:String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(this)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            cekIzin()
                            dataIzin()
                            Toast.makeText(this@IzinActivity,"Surat berhasil terkirim",Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","kirimEdit")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("image",imStr)
                data.put("surat",surat)
                data.put("keterangan",keterangan)
                data.put("kode",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun kirimNotifikasi(surat:String){
        val client = AsyncHttpClient()
        val params = RequestParams()
        preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        params.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
        params.put("surat", surat)
        params.put("pilihan", "pesertaIzin")
        params.put("event", "pesertaIzin")

        client.post(
            "http://192.168.43.57/simaptapkl/public/service/kirimNotifikasi.php",
            params,
            object : AsyncHttpResponseHandler() {
                override fun onSuccess(
                    statusCode: Int,
                    headers: Array<Header?>?,
                    responseBody: ByteArray?
                ) {
                }

                override fun onFailure(
                    statusCode: Int,
                    headers: Array<Header?>?,
                    responseBody: ByteArray?,
                    error: Throwable?
                ) {
                }
            })
    }

    fun cekIzin(){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    tmbhIzin.isEnabled = false
                }else if(respon.equals("2")){
                    tmbhIzin.isEnabled = false
                }else if(respon.equals("10")){
                    tmbhIzin.isEnabled = false
                }else{
                    tmbhIzin.isEnabled = true
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","cekizin")
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
                val statusMahasiswa = jsonObject.getString("STATUS_PST")

                if(!statusMahasiswa.equals("aktif")){
                    tmbhIzin.isEnabled = false
                }

            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","home")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun dataIzin(){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                daftarForum.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("status_surat",jsonObject.getString("STATUS_SURAT"))
                    frm.put("keterangan",jsonObject.getString("KETERANGAN"))
                    frm.put("tanggal",jsonObject.getString("tanggal"))
                    frm.put("id",jsonObject.getString("ID"))
                    frm.put("surat",jsonObject.getString("url"))

                    daftarForum.add(frm)
                }
                forumIzin.notifyDataSetChanged()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","view")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.add(request)
    }

    fun getJalan(lat:Double, long:Double): String{
        var namaJalan = ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var jalan = geocoder.getFromLocation(lat, long,1)

        try {
            namaJalan = jalan.get(0).thoroughfare
        }catch (e: Exception){
            namaJalan
        }

        return namaJalan
    }

    fun getDesa(lat:Double, long:Double): String{
        var namaDesa = ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var desa = geocoder.getFromLocation(lat, long,1)

        try {
            namaDesa = desa.get(0).subLocality
        }catch (e: Exception){
            namaDesa
        }

        return namaDesa
    }

    fun getKecamatan(lat:Double, long:Double): String{
        var namaKecamatan = ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var kecamatan = geocoder.getFromLocation(lat, long,1)

        try {
            namaKecamatan = kecamatan.get(0).locality
        }catch (e: Exception){
            namaKecamatan
        }

        return namaKecamatan
    }

    fun getKabupaten(lat:Double, long:Double): String{
        var namaKabupaten = ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var kabupaten = geocoder.getFromLocation(lat, long,1)

        try {
            namaKabupaten = kabupaten.get(0).subAdminArea
        }catch (e: Exception){
            namaKabupaten
        }

        return namaKabupaten
    }

    fun getProvinsi(lat:Double, long:Double): String{
        var namaProvinsi= ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var provinsi = geocoder.getFromLocation(lat, long,1)

        try {
            namaProvinsi = provinsi.get(0).adminArea
        }catch (e: Exception){
            namaProvinsi
        }

        return namaProvinsi
    }

    fun getKodepos(lat:Double, long:Double): String{
        var namaKodepos= ""
        var geocoder = Geocoder(this, Locale.getDefault())
        var kodePos = geocoder.getFromLocation(lat, long,1)

        try {
            namaKodepos = kodePos.get(0).postalCode
        }catch (e: Exception){
            namaKodepos
        }

        return namaKodepos
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