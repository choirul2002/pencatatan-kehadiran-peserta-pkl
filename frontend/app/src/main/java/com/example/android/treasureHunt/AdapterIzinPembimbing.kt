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
import com.google.android.material.textfield.TextInputEditText
import com.loopj.android.http.AsyncHttpClient
import com.loopj.android.http.AsyncHttpResponseHandler
import com.loopj.android.http.RequestParams
import com.squareup.picasso.Picasso
import cz.msebera.android.httpclient.Header
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_surat.*
import org.json.JSONObject
import java.util.*
import kotlin.collections.HashMap

class AdapterIzinPembimbing(val dataForum:List<HashMap<String,String>>, val suratActivity: SuratActivity) : RecyclerView.Adapter<AdapterIzinPembimbing.HolderDataForum>(),
    Filterable {
    private var filteredList: List<HashMap<String, String>> = dataForum
    val perizinan = "http://192.168.43.57/simaptapkl/public/service/perizinan.php"
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterIzinPembimbing.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_izin_pembimbing,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterIzinPembimbing.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        holder.peserta.setText(data.get("peserta"))
        holder.asal.setText(data.get("asal"))
        holder.tanggal.setText(data.get("tanggal"))
        Picasso.get().load(data.get("foto")).into(holder.foto)
        holder.click.setOnClickListener {
            suratActivity.lihat = "2"
            suratActivity.searchItem.isVisible = false
            suratActivity.micItem.isVisible = false
            suratActivity.supportActionBar?.setTitle("Foto Surat")
            suratActivity.supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(Color.BLACK))
            }

            Picasso.get().load(data.get("surat")).into(suratActivity.imageView19)

            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    suratActivity.imageView19.scaleX = value
                    suratActivity.imageView19.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(suratActivity.fLayout, "alpha", 0f, 1f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
            }

            suratActivity.fLayout.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                        suratActivity.imageView19.scaleX = 0f
                        suratActivity.imageView19.scaleY = 0f
                    }
                }
            })

            suratActivity.window.statusBarColor = ContextCompat.getColor(suratActivity, R.color.black)

            suratActivity.fLayout.visibility = View.VISIBLE
            animatorSet.start()
        }
        holder.click.setOnLongClickListener(View.OnLongClickListener(){
            popupMenus(it, data.get("idIzin").toString(), data.get("surat").toString(), data.get("peserta").toString(), data.get("kd_peserta").toString())
        })

        holder.foto.setOnClickListener {
            suratActivity.lihat = "1"
            suratActivity.searchItem.isVisible = false
            suratActivity.micItem.isVisible = false
            suratActivity.supportActionBar?.setTitle(data.get("peserta").toString())
            suratActivity.supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(Color.BLACK))
            }

            Picasso.get().load(data.get("foto")).into(suratActivity.imageView19)

            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    suratActivity.imageView19.scaleX = value
                    suratActivity.imageView19.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(suratActivity.fLayout, "alpha", 0f, 1f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
            }

            suratActivity.fLayout.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                        suratActivity.imageView19.scaleX = 0f
                        suratActivity.imageView19.scaleY = 0f
                    }
                }
            })

            suratActivity.window.statusBarColor = ContextCompat.getColor(suratActivity, R.color.black)

            suratActivity.fLayout.visibility = View.VISIBLE
            animatorSet.start()
        }
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val peserta = v.findViewById<TextView>(R.id.textView19)
        val asal = v.findViewById<TextView>(R.id.textView20)
        val click = v.findViewById<CardView>(R.id.cardView11)
        val foto = v.findViewById<CircleImageView>(R.id.profile_image)
        val tanggal = v.findViewById<TextView>(R.id.textView46)
    }

    @SuppressLint("MissingInflatedId")
    private fun popupMenus(v: View, kode: String, surat: String, peserta: String, kodePeserta: String): Boolean {
        val popupMenus = PopupMenu(suratActivity,v)
        popupMenus.inflate(R.menu.menu_popup_pembimbing)
        popupMenus.setOnMenuItemClickListener {
            when(it.itemId){
                R.id.itemApprove->{
                    cekjamkerjaapprove(suratActivity, kode, kodePeserta, surat)

                    true
                }
                R.id.itemDisapprove->{
                    cekjamkerjadisapprove(suratActivity, kode, kodePeserta, surat)

                    true
                }
                else->true
            }
        }

        popupMenus.show()

        return false
    }

    private fun kirimNotifikasi(context: Context, kodePeserta: String, surat: String, statusSurat:String, alasan:String){
        val client = AsyncHttpClient()
        val params = RequestParams()
        preferences = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        params.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
        params.put("event", "pembimbing")
        params.put("pilihan", "izin")
        params.put("kodePeserta", surat)
        params.put("surat", kodePeserta)
        params.put("statusSurat", statusSurat)
        params.put("alasan", alasan)

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

    fun validasiSurat(context: Context, kodeAbsensi: String, kodePeserta: String, surat: String, statusSurat:String, alasan:String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(suratActivity)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            kirimNotifikasi(context, kodePeserta, surat, statusSurat, alasan)
                            suratActivity.dataIzinPembimbing()
                            suratActivity.jumlahIzinWaiting()
                            Toast.makeText(suratActivity,"Berhasil tervalidasi",Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(suratActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","validasiSurat")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("idSurat",kodeAbsensi)
                data.put("statusSurat",statusSurat)

                return data
            }
        }
        val queue = Volley.newRequestQueue(suratActivity)
        queue.add(request)
    }

    fun validasiSuratKode(context: Context, kodeAbsensi: String, kodePeserta: String, surat: String, statusSurat:String, alasan:String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(suratActivity)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            kirimNotifikasi(context, kodePeserta, surat, statusSurat, alasan)
                            suratActivity.onBackPressed()
                            Toast.makeText(suratActivity,"Berhasil tervalidasi",Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(suratActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","validasiSurat")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("idSurat",kodeAbsensi)
                data.put("statusSurat",statusSurat)

                return data
            }
        }
        val queue = Volley.newRequestQueue(suratActivity)
        queue.add(request)
    }

    fun cekjamkerjaapprove(context: Context, kode: String, surat: String, kodePeserta: String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    if(suratActivity.perpindahan.equals("1")){
                        val inflater = LayoutInflater.from(suratActivity)
                        val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                        val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                        val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                        val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                        kembali.setText("Kembali")
                        kirim.setText("Kirim")
                        informasi.setText("Apakah anda yakin menyetujui surat izin ini?")

                        kembali.setOnClickListener{
                            suratActivity.alertDialog.dismiss()
                        }

                        kirim.setOnClickListener{
                            suratActivity.alertDialog.dismiss()
                            validasiSuratKode(suratActivity, kode, kodePeserta, surat, "approve", "")
                        }

                        val builder = android.app.AlertDialog.Builder(suratActivity)
                        builder.setView(dialogView)
                        suratActivity.alertDialog = builder.create()
                        suratActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                        suratActivity.alertDialog.show()
                        suratActivity.jumlahIzinWaiting()
                    }else{
                        val inflater = LayoutInflater.from(suratActivity)
                        val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                        val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                        val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                        val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                        kembali.setText("Kembali")
                        kirim.setText("Kirim")
                        informasi.setText("Apakah anda yakin menyetujui surat izin ini?")

                        kembali.setOnClickListener{
                            suratActivity.alertDialog.dismiss()
                        }

                        kirim.setOnClickListener{
                            suratActivity.alertDialog.dismiss()
                            validasiSurat(suratActivity, kode, kodePeserta, surat, "approve", "")
                        }

                        val builder = android.app.AlertDialog.Builder(suratActivity)
                        builder.setView(dialogView)
                        suratActivity.alertDialog = builder.create()
                        suratActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                        suratActivity.alertDialog.show()
                        suratActivity.jumlahIzinWaiting()
                    }
                }else{
                    Toast.makeText(suratActivity, "Diluar jam masuk tidak bisa diproses", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(suratActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","cekjamkerja")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(suratActivity)
        queue.add(request)
    }

    fun cekjamkerjadisapprove(context: Context, kode: String, surat: String, kodePeserta: String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    if(suratActivity.perpindahan.equals("1")){
                        val inflater = LayoutInflater.from(suratActivity)
                        val dialogView = inflater.inflate(R.layout.activity_form_alasan,null)
                        val informasi = dialogView.findViewById<TextView>(R.id.textView137)
                        val kirim = dialogView.findViewById<TextView>(R.id.textView138)
                        val kembali = dialogView.findViewById<TextView>(R.id.textView139)
                        val formAlasan = dialogView.findViewById<TextInputEditText>(R.id.formAlasan)

                        kembali.setText("Kembali")
                        kirim.setText("Kirim")
                        informasi.setText("Apakah anda yakin tidak menyetujui surat izin?")

                        kembali.setOnClickListener{
                            suratActivity.alertDialog.dismiss()
                        }

                        kirim.setOnClickListener{
                            if(formAlasan.text.toString().equals("")){
                                Toast.makeText(suratActivity, "Form alasan kosong", Toast.LENGTH_SHORT).show()
                            }else {
                                suratActivity.alertDialog.dismiss()
                                validasiSuratKode(suratActivity, kode, kodePeserta, surat, "disapprove", formAlasan.text.toString())
                            }
                        }

                        val builder = android.app.AlertDialog.Builder(suratActivity)
                        builder.setView(dialogView)
                        suratActivity.alertDialog = builder.create()
                        suratActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                        suratActivity.alertDialog.show()
                    }else{
                        val inflater = LayoutInflater.from(suratActivity)
                        val dialogView = inflater.inflate(R.layout.activity_form_alasan,null)
                        val informasi = dialogView.findViewById<TextView>(R.id.textView137)
                        val kirim = dialogView.findViewById<TextView>(R.id.textView138)
                        val kembali = dialogView.findViewById<TextView>(R.id.textView139)
                        val formAlasan = dialogView.findViewById<TextInputEditText>(R.id.formAlasan)

                        kembali.setText("Kembali")
                        kirim.setText("Kirim")
                        informasi.setText("Apakah anda yakin tidak menyetujui surat izin?")

                        kembali.setOnClickListener{
                            suratActivity.alertDialog.dismiss()
                        }

                        kirim.setOnClickListener{
                            if(formAlasan.text.toString().equals("")){
                                Toast.makeText(suratActivity, "Form alasan kosong", Toast.LENGTH_SHORT).show()
                            }else {
                                suratActivity.alertDialog.dismiss()
                                validasiSurat(suratActivity, kode, kodePeserta, surat, "disapprove", formAlasan.text.toString())
                            }
                        }

                        val builder = android.app.AlertDialog.Builder(suratActivity)
                        builder.setView(dialogView)
                        suratActivity.alertDialog = builder.create()
                        suratActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                        suratActivity.alertDialog.show()
                    }
                }else{
                    Toast.makeText(suratActivity, "Diluar jam masuk tidak bisa diproses", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(suratActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = HashMap<String,String>()
                data.put("pilihan","cekjamkerja")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())

                return data
            }
        }
        val queue = Volley.newRequestQueue(suratActivity)
        queue.add(request)
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
                            if (item["peserta"]?.toLowerCase()?.contains(filterPattern) == true) {
                                filteredDataList.add(item)
                                break
                            }
                            else if(item["asal"]?.toLowerCase()?.contains(filterPattern) == true){
                                filteredDataList.add(item)
                                break
                            }
                            else if(item["kategori"]?.toLowerCase()?.contains(filterPattern) == true){
                                filteredDataList.add(item)
                                break
                            }
                            else if(item["tanggal"]?.toLowerCase()?.contains(filterPattern) == true){
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
                    suratActivity.notFound()
                }else{
                    suratActivity.found()
                }
                notifyDataSetChanged()
            }
        }
    }
}