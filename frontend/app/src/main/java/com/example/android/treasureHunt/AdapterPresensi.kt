package com.example.android.treasureHunt

import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.annotation.SuppressLint
import android.content.Context
import android.content.DialogInterface
import android.content.Intent
import android.content.SharedPreferences
import android.graphics.Color
import android.graphics.drawable.ColorDrawable
import android.location.Geocoder
import android.location.Location
import android.net.Uri
import android.os.AsyncTask
import android.os.Handler
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.*
import androidx.appcompat.app.AlertDialog
import androidx.cardview.widget.CardView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import com.github.barteksc.pdfviewer.PDFView
import com.google.android.material.textfield.TextInputEditText
import com.loopj.android.http.AsyncHttpClient
import com.loopj.android.http.AsyncHttpResponseHandler
import com.loopj.android.http.RequestParams
import com.squareup.picasso.Picasso
import cz.msebera.android.httpclient.Header
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_presensi_peserta.*
import layout.transitions.library.Fade
import layout.transitions.library.Transitions
import mumayank.com.airlocationlibrary.AirLocation
import org.json.JSONObject
import java.io.File
import java.io.FileOutputStream
import java.net.URL
import java.util.*
import kotlin.collections.HashMap

class AdapterPresensi(val dataForum:List<HashMap<String,String>>, val presensiActivity: PresensiActivity) : RecyclerView.Adapter<AdapterPresensi.HolderDataForum>(),
    Filterable {
    private var filteredList: List<HashMap<String, String>> = dataForum
    private val VIEW_TYPE_1 = 1
    private val VIEW_TYPE_2 = 2
    private val VIEW_TYPE_3 = 3
    private val VIEW_TYPE_4 = 4

    override fun getItemViewType(position: Int): Int {
        val kode = filteredList[position]["kd_mhs"] as String

        return if (kode.equals("pembatas")) VIEW_TYPE_1 else if(kode.equals("menunggu")) VIEW_TYPE_3 else if(kode.equals("pulang")) VIEW_TYPE_4 else VIEW_TYPE_2
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterPresensi.HolderDataForum {
        return if (viewType == VIEW_TYPE_1) {
            val v = LayoutInflater.from(parent.context).inflate(R.layout.row_pembatas,parent,false)
            HolderDataForum(v)
        } else if(viewType == VIEW_TYPE_3) {
            val v = LayoutInflater.from(parent.context).inflate(R.layout.row_pembatas_menunggu,parent,false)
            HolderDataForum(v)
        } else if(viewType == VIEW_TYPE_4) {
            val v = LayoutInflater.from(parent.context).inflate(R.layout.row_pembatas_cek_pulang,parent,false)
            HolderDataForum(v)
        } else {
            val v = LayoutInflater.from(parent.context).inflate(R.layout.row_presensi,parent,false)
            HolderDataForum(v)
        }
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterPresensi.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        val kode = filteredList[position]["kd_mhs"] as String

        if(!kode.equals("pembatas") && !kode.equals("menunggu") && !kode.equals("pulang")){
            holder.peserta.setText(data.get("namaMhs").toString())
            holder.kampus.setText(data.get("namaKmps").toString())
            holder.tanggal.setText(data.get("tanggal").toString())
            Picasso.get().load(data.get("profil")).into(holder.foto)

            if(data.get("status").equals("izin")){
                if(data.get("status_surat").equals("approve")){
                    holder.tanda.setBackgroundResource(R.drawable.notifizin)
                }else{
                    holder.tanda.setBackgroundResource(R.drawable.notifmenunggu)
                }
            }else if(data.get("status").equals("hadir")){
                holder.tanda.setBackgroundResource(R.drawable.notifhadir)
            }else{
                holder.tanda.setBackgroundResource(R.drawable.notifkosong)
            }

            holder.card.setOnLongClickListener(View.OnLongClickListener(){
//                if(!data.get("check_in").equals("null") && data.get("check_out").equals("null")){
//                    popupMenusPerbarui(it, data.get("kd_mhs").toString(), data.get("namaMhs").toString())
//                }else{
//                    popupMenus(it, data.get("kd_mhs").toString(), data.get("namaMhs").toString())
//                }
                jamkerjapulang(it, data.get("kd_mhs").toString(), data.get("namaMhs").toString(), data.get("check_in").toString(), data.get("check_out").toString())
            })

            holder.card.setOnClickListener {
                if(data.get("status").equals("izin")){
                    if(data.get("status_surat").equals("approve")){
                        presensiActivity.lihat = "1"
                        presensiActivity.supportActionBar?.setTitle("Foto Surat")
                        presensiActivity.supportActionBar?.apply {
                            setBackgroundDrawable(ColorDrawable(Color.BLACK))
                        }

                        presensiActivity.searchItem.isVisible = false
                        presensiActivity.micItem.isVisible = false
                        Picasso.get().load(data.get("surat")).into(presensiActivity.imageView19)

                        val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                            duration = 250
                            addUpdateListener {
                                val value = it.animatedValue as Float
                                presensiActivity.imageView19.scaleX = value
                                presensiActivity.imageView19.scaleY = value
                            }
                        }

                        val visibilityAnimator = ObjectAnimator.ofFloat(presensiActivity.fLayout, "alpha", 0f, 1f).apply {
                            duration = 250
                        }

                        val animatorSet = AnimatorSet().apply {
                            playTogether(animator, visibilityAnimator)
                        }

                        presensiActivity.fLayout.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                                    presensiActivity.imageView19.scaleX = 0f
                                    presensiActivity.imageView19.scaleY = 0f
                                }
                            }
                        })

                        presensiActivity.window.statusBarColor = ContextCompat.getColor(presensiActivity, R.color.black)

                        presensiActivity.fLayout.visibility = View.VISIBLE
                        animatorSet.start()
                    }else if(data.get("status_surat").equals("waiting")){
                        val intent = Intent(presensiActivity, SuratActivity::class.java)
                        intent.putExtra("perpindahan", "1")
                        intent.putExtra("kodemahasiswa", data.get("kd_mhs").toString())
                        presensiActivity.startActivityForResult(intent,10)
                        val transitions = Transitions(presensiActivity)
                        transitions.setAnimation(Fade().InRight())
                    }else{
                        val intent = Intent(presensiActivity, KosongActivity::class.java)
                        intent.putExtra("perpindahan", "1")
                        intent.putExtra("kodemahasiswa", data.get("kd_mhs").toString())
                        presensiActivity.startActivity(intent)
                        val transitions = Transitions(presensiActivity)
                        transitions.setAnimation(Fade().InRight())
                    }
                }else{
                    if(data.get("status").equals("hadir")){
                        val inflater = LayoutInflater.from(presensiActivity)
                        val dialogView = inflater.inflate(R.layout.activity_form_informasi_pembimbing,null)
                        val kembali = dialogView.findViewById<TextView>(R.id.textView128)
                        val formMasuk = dialogView.findViewById<TextView>(R.id.textView48)
                        val lokasiMasuk = dialogView.findViewById<TextView>(R.id.textView47)
                        val formPulang = dialogView.findViewById<TextView>(R.id.textView49)
                        val lokasiPulang = dialogView.findViewById<TextView>(R.id.textView50)
                        val formKehadiran = dialogView.findViewById<TextView>(R.id.textView41)
                        val formKegiatan = dialogView.findViewById<TextView>(R.id.textView145)

                        if(data.get("kehadiran").toString().equals("tepat waktu")){
                            formKehadiran.setText("Tepat waktu")
                        }else if(data.get("kehadiran").toString().equals("telat")){
                            formKehadiran.setText("Tepat waktu")
                        }else{
                            formKehadiran.setText("Terlambat")
                        }

                        formMasuk.setText(data.get("check_in").toString()+" WIB")
                        lokasiMasuk.setText(data.get("lokasi_masuk").toString())

                        if(data.get("check_out").toString().equals("null")){
                            formPulang.setText("-")
                            lokasiPulang.setText("-")
                            formKegiatan.setText("-")
                        }else{
                            formPulang.setText(data.get("check_out").toString()+" WIB")
                            lokasiPulang.setText(data.get("lokasi_pulang").toString())
                            formKegiatan.setText(data.get("kegiatan").toString())
                        }

                        kembali.setText("Kembali")

                        kembali.setOnClickListener{
                            presensiActivity.alertDialog.dismiss()
                        }

                        val builder = android.app.AlertDialog.Builder(presensiActivity)
                        builder.setView(dialogView)
                        presensiActivity.alertDialog = builder.create()
                        presensiActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                        presensiActivity.alertDialog.show()
                    }else if(data.get("status").equals("null")){
                        val intent = Intent(presensiActivity, KosongActivity::class.java)
                        intent.putExtra("perpindahan", "1")
                        intent.putExtra("kodemahasiswa", data.get("kd_mhs").toString())
                        presensiActivity.startActivity(intent)
                        val transitions = Transitions(presensiActivity)
                        transitions.setAnimation(Fade().InRight())
                    }
                }
            }

            holder.foto.setOnClickListener {
                presensiActivity.lihat = "1"
                presensiActivity.searchItem.isVisible = false
                presensiActivity.micItem.isVisible = false
                presensiActivity.supportActionBar?.setTitle(data.get("namaMhs").toString())
                presensiActivity.supportActionBar?.apply {
                    setBackgroundDrawable(ColorDrawable(Color.BLACK))
                }

                Picasso.get().load(data.get("profil")).into(presensiActivity.imageView19)

                val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                    duration = 250
                    addUpdateListener {
                        val value = it.animatedValue as Float
                        presensiActivity.imageView19.scaleX = value
                        presensiActivity.imageView19.scaleY = value
                    }
                }

                val visibilityAnimator = ObjectAnimator.ofFloat(presensiActivity.fLayout, "alpha", 0f, 1f).apply {
                    duration = 250
                }

                val animatorSet = AnimatorSet().apply {
                    playTogether(animator, visibilityAnimator)
                }

                presensiActivity.fLayout.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                            presensiActivity.imageView19.scaleX = 0f
                            presensiActivity.imageView19.scaleY = 0f
                        }
                    }
                })

                presensiActivity.window.statusBarColor = ContextCompat.getColor(presensiActivity, R.color.black)

                presensiActivity.fLayout.visibility = View.VISIBLE
                animatorSet.start()
            }
        }
    }

    private fun jamkerjapulang(v: View, kode: String, nama:String, masuk:String, pulang:String): Boolean{
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    if(!masuk.equals("null") && pulang.equals("null")){
                        popupMenusPerbarui(v, kode, nama)
                    }else{
                        popupMenus(v, kode, nama)
                    }
                }else{
                    popupMenus(v, kode, nama)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(presensiActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = presensiActivity.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","cekjampulangkerja")
                data.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(presensiActivity)
        queue.add(request)

        return false
    }

    private fun popupMenus(v: View, kode: String, nama:String): Boolean {
        val popupMenus = PopupMenu(presensiActivity,v)
        popupMenus.inflate(R.menu.menu_popup_presensi_pembimbing)
        popupMenus.setOnMenuItemClickListener {
            when(it.itemId){
                R.id.itemAbs->{
                    presensiActivity.lihat = "2"
                    rekapAbsensi(presensiActivity, nama, kode)
                    true
                }

                else->true
            }
        }

        popupMenus.show()

        return false
    }

    private fun popupMenusPerbarui(v: View, kode: String, nama:String): Boolean {
        val popupMenus = PopupMenu(presensiActivity,v)
        popupMenus.inflate(R.menu.menu_popup_presensi_pembimbing_perbarui)
        popupMenus.setOnMenuItemClickListener {
            when(it.itemId){
                R.id.itemAbs->{
                    presensiActivity.lihat = "2"
                    rekapAbsensi(presensiActivity, nama, kode)
                    true
                }
                R.id.itemPerbarui->{
                    presensiActivity.airLoc = AirLocation(presensiActivity,true,true,
                        object : AirLocation.Callbacks{
                            override fun onFailed(locationFailedEnum: AirLocation.LocationFailedEnum) {
                                Toast.makeText(presensiActivity,"Gagal mendapatkan lokasi saat ini", Toast.LENGTH_LONG).show()
                            }

                            override fun onSuccess(location: Location) {
                                cekjamperbarui(kode,
                                    getJalan(location.latitude, location.longitude),
                                    getDesa(location.latitude, location.longitude),
                                    getKecamatan(location.latitude, location.longitude),
                                    getKabupaten(location.latitude, location.longitude),
                                    getProvinsi(location.latitude, location.longitude),
                                    getKodepos(location.latitude, location.longitude),
                                    "melanjutkan proyek"
                                )
                            }
                        })

                    true
                }

                else->true
            }
        }

        popupMenus.show()

        return false
    }

    val absen = "http://192.168.43.57/simaptapkl/public/service/absensi.php"
    fun cekout(kode:String, jln:String, ds:String, kec:String, kab:String, prov:String, kp:String, kegiatan:String){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(presensiActivity)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            presensiActivity.dataPresensi()
                            presensiActivity.jumlahDataPresensi()
                            kirimNotifikasi(kode)
                            Toast.makeText(presensiActivity,"Berhasil diperbarui",Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(presensiActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = presensiActivity.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","cekoutperbarui")
                data.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("kode",kode)
                data.put("jalan",jln)
                data.put("desa",ds)
                data.put("kecamatan",kec)
                data.put("kabupaten",kab)
                data.put("provinsi",prov)
                data.put("kodepos",kp)
                data.put("kegiatan",kegiatan)

                return data
            }
        }
        val queue = Volley.newRequestQueue(presensiActivity)
        queue.add(request)
    }

    fun cekjamperbarui(kode:String, jln:String, ds:String, kec:String, kab:String, prov:String, kp:String, kegiatan:String){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val inflater = presensiActivity.layoutInflater
                    val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                    val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                    val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                    val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                    kembali.setText("Kembali")
                    kirim.setText("Perbarui")
                    informasi.setText("Apakah anda yakin ingin memperbarui absen pulang peserta PKL ini ?")

                    kembali.setOnClickListener{
                        presensiActivity.alertDialog.dismiss()
                    }

                    kirim.setOnClickListener{
                        presensiActivity.alertDialog.dismiss()
                        cekout(kode,jln,ds,kec,kab,prov,kp,kegiatan)
                    }

                    val builder = android.app.AlertDialog.Builder(presensiActivity)
                    builder.setView(dialogView)
                    presensiActivity.alertDialog = builder.create()
                    presensiActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                    presensiActivity.alertDialog.show()
                }else{
                    Toast.makeText(presensiActivity,"System tidak dapat memperbarui",Toast.LENGTH_LONG).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(presensiActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = presensiActivity.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","jamcekoutperbarui")
                data.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(presensiActivity)
        queue.add(request)
    }

    fun kirimNotifikasi(kode:String){
        val client = AsyncHttpClient()
        val params = RequestParams()
        preferences = presensiActivity.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        params.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
        params.put("pilihan", "berhasilperbaruiabsenpulang")
        params.put("kode", kode)
        params.put("event", "berhasilperbarui")

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

    fun getJalan(lat:Double, long:Double): String{
        var namaJalan = ""
        var geocoder = Geocoder(presensiActivity, Locale.getDefault())
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
        var geocoder = Geocoder(presensiActivity, Locale.getDefault())
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
        var geocoder = Geocoder(presensiActivity, Locale.getDefault())
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
        var geocoder = Geocoder(presensiActivity, Locale.getDefault())
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
        var geocoder = Geocoder(presensiActivity, Locale.getDefault())
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
        var geocoder = Geocoder(presensiActivity, Locale.getDefault())
        var kodePos = geocoder.getFromLocation(lat, long,1)

        try {
            namaKodepos = kodePos.get(0).postalCode
        }catch (e: Exception){
            namaKodepos
        }

        return namaKodepos
    }

    val cetak = "http://192.168.43.57/simaptapkl/public/service/cetak.php"
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""

    fun rekapAbsensi(context: Context, nama:String, kode:String){
        val request = object : StringRequest(
            Method.POST,cetak,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val file = jsonObject.getString("file")

                if(!file.isEmpty()){
                    val loading = LoadingDialog(presensiActivity)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()

                            val pdfView = presensiActivity.findViewById<PDFView>(R.id.pdfViewPembimbing)
                            val urlLihat = "http://192.168.43.57/simaptapkl/public/service/generate/"+file

                            val downloadTask = object : AsyncTask<Void, Void, File>() {
                                override fun doInBackground(vararg params: Void?): File {
                                    val inputStream = URL(urlLihat).openStream()
                                    val file = File(context.cacheDir, "file.pdf")
                                    FileOutputStream(file).use { outputStream ->
                                        val buffer = ByteArray(4 * 1024)
                                        var bytesRead = inputStream.read(buffer)
                                        while (bytesRead != -1) {
                                            outputStream.write(buffer, 0, bytesRead)
                                            bytesRead = inputStream.read(buffer)
                                        }
                                        outputStream.flush()
                                    }
                                    return file
                                }

                                override fun onPostExecute(result: File) {
                                    pdfView.fromFile(result).load()
                                }
                            }

                            downloadTask.execute()

                            presensiActivity.supportActionBar?.setTitle(nama)
                            presensiActivity.supportActionBar?.apply {
                                setBackgroundDrawable(ColorDrawable(Color.BLACK))
                            }

                            presensiActivity.searchItem.isVisible = false
                            presensiActivity.micItem.isVisible = false

                            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                                duration = 250
                                addUpdateListener {
                                    val value = it.animatedValue as Float
                                    pdfView.scaleX = value
                                    pdfView.scaleY = value
                                }
                            }

                            val visibilityAnimator = ObjectAnimator.ofFloat(presensiActivity.flAbsensiViewPembimbing, "alpha", 0f, 1f).apply {
                                duration = 250
                            }

                            val animatorSet = AnimatorSet().apply {
                                playTogether(animator, visibilityAnimator)
                            }

                            presensiActivity.flAbsensiViewPembimbing.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                                        pdfView.scaleX = 0f
                                        pdfView.scaleY = 0f
                                    }
                                }
                            })

                            presensiActivity.window.statusBarColor = ContextCompat.getColor(context, R.color.black)

                            presensiActivity.flAbsensiViewPembimbing.visibility = View.VISIBLE
                            animatorSet.start()
                        }
                    },3000)
                }

            },
            Response.ErrorListener { error ->
                Toast.makeText(presensiActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","rekapKehadiranPembimbing")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("idMhs",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(presensiActivity)
        queue.add(request)
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val foto = v.findViewById<CircleImageView>(R.id.profile_image)
        val tanda = v.findViewById<CircleImageView>(R.id.profile_image2)
        val peserta = v.findViewById<TextView>(R.id.textViewNama)
        val kampus = v.findViewById<TextView>(R.id.textView35)
        val tanggal = v.findViewById<TextView>(R.id.textView46)
        val card = v.findViewById<CardView>(R.id.cardView11)
    }

    override fun getFilter(): Filter {
        return object : Filter() {
            override fun performFiltering(constraint: CharSequence?): FilterResults {
                val filteredDataList = mutableListOf<HashMap<String, String>>()

                if (constraint.isNullOrEmpty()) {
                    filteredDataList.addAll(dataForum)
                } else {
                    val filterPattern = constraint.toString().toLowerCase().trim()
                    for (item in dataForum) {
                        for (key in item.keys) {
                            if (item["namaMhs"]?.toLowerCase()?.contains(filterPattern) == true) {
                                filteredDataList.add(item)
                                break
                            }else if(item["namaKmps"]?.toLowerCase()?.contains(filterPattern) == true){
                                filteredDataList.add(item)
                                break
                            }else if(item["tanggal"]?.toLowerCase()?.contains(filterPattern) == true){
                                filteredDataList.add(item)
                                break
                            }else if(item["status"]?.toLowerCase()?.contains(filterPattern) == true){
                                filteredDataList.add(item)
                                break
                            }else if(item["kehadiran"]?.toLowerCase()?.contains(filterPattern) == true){
                                filteredDataList.add(item)
                                break
                            }else if(item["kategori"]?.toLowerCase()?.contains(filterPattern) == true){
                                filteredDataList.add(item)
                                break
                            }
                        }
                    }
                }

                val results = FilterResults()
                results.values = filteredDataList
                return results
            }

            override fun publishResults(constraint: CharSequence?, results: FilterResults?) {
                filteredList = results?.values as List<HashMap<String, String>>
                if(filteredList.isEmpty()){
                    presensiActivity.notFound()
                }else{
                    presensiActivity.found()
                }
                notifyDataSetChanged()
            }
        }
    }
}