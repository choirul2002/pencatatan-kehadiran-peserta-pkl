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
import android.net.Uri
import android.os.Handler
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.view.Window
import android.widget.*
import androidx.appcompat.app.AlertDialog
import androidx.cardview.widget.CardView
import androidx.core.content.ContextCompat
import androidx.core.content.ContextCompat.startActivity
import androidx.core.view.isVisible
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.bottomsheet.BottomSheetDialog
import com.google.android.material.textfield.TextInputEditText
import com.loopj.android.http.AsyncHttpClient
import com.loopj.android.http.AsyncHttpResponseHandler
import com.loopj.android.http.RequestParams
import com.squareup.picasso.Picasso
import cz.msebera.android.httpclient.Header
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_kosong.*
import org.json.JSONObject
import org.w3c.dom.Text

class AdapterKosong(val dataForum:List<HashMap<String,String>>, val kosongActivity: KosongActivity) : RecyclerView.Adapter<AdapterKosong.HolderDataForum>(), Filterable{
    private var filteredList: List<HashMap<String, String>> = dataForum
    val absen = "http://192.168.43.57/simaptapkl/public/service/absensi.php"

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterKosong.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_kosong,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterKosong.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        holder.nama.setText(data.get("namaMhs").toString())
        holder.tanggal.setText(data.get("tanggal").toString())
        holder.kampus.setText(data.get("namaKmps").toString())
        Picasso.get().load(data.get("profil")).into(holder.foto)

        holder.card.setOnLongClickListener(View.OnLongClickListener(){
            kosong()
        })

        holder.card.setOnClickListener {
            popupMenus(it, data.get("kodeMhs").toString(), data.get("wa").toString())
        }

        holder.foto.setOnClickListener {
            kosongActivity.lihat = "1"
            kosongActivity.searchItem.isVisible = false
            kosongActivity.micItem.isVisible = false
            kosongActivity.supportActionBar?.setTitle(data.get("namaMhs").toString())
            kosongActivity.supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(Color.BLACK))
            }

            Picasso.get().load(data.get("profil")).into(kosongActivity.imageView19)

            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    kosongActivity.imageView19.scaleX = value
                    kosongActivity.imageView19.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(kosongActivity.fLayout, "alpha", 0f, 1f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
            }

            kosongActivity.fLayout.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                        kosongActivity.imageView19.scaleX = 0f
                        kosongActivity.imageView19.scaleY = 0f
                    }
                }
            })

            kosongActivity.window.statusBarColor = ContextCompat.getColor(kosongActivity, R.color.black)

            kosongActivity.fLayout.visibility = View.VISIBLE
            animatorSet.start()
        }
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val foto = v.findViewById<CircleImageView>(R.id.profile_image)
        val nama = v.findViewById<TextView>(R.id.textViewNama)
        val tanggal = v.findViewById<TextView>(R.id.textView46)
        val kampus = v.findViewById<TextView>(R.id.textView35)
        val card = v.findViewById<CardView>(R.id.cardView11)
    }

    private fun kosong(): Boolean{
        return true
    }

    private fun popupMenus(v: View, kode: String, wa:String): Boolean {
        val popupMenus = PopupMenu(kosongActivity,v)
        popupMenus.inflate(R.menu.menu_popup_kosong)
        popupMenus.setOnMenuItemClickListener {
            when(it.itemId){
                R.id.itemNotifikasi->{
                    cekjamkerjanotifikasi(kosongActivity, kode)

                    true
                }
                R.id.itemWhatshapp->{
                    cekjamkerjawhatshapp(kosongActivity, wa, v)

                    true
                }
                else->true
            }
        }

        popupMenus.show()

        return false
    }

    fun cekjamkerjanotifikasi(context: Context, kode: String){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(kosongActivity)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            kirimNotifikasi(kosongActivity, kode)
                            Toast.makeText(kosongActivity,"Berhasil Terkirim",Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }else{
                    Toast.makeText(kosongActivity, "Diluar jam masuk tidak bisa diproses", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(kosongActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
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
        val queue = Volley.newRequestQueue(kosongActivity)
        queue.add(request)
    }

    fun cekjamkerjawhatshapp(context: Context, wa: String, v: View){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(kosongActivity)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            val packageManager = kosongActivity.packageManager
                            val whatsappIntent = Intent(Intent.ACTION_SENDTO, Uri.parse("smsto:" + wa + "@s.whatsapp.net"))
                            whatsappIntent.setPackage("com.whatsapp")
                            if (whatsappIntent.resolveActivity(packageManager) != null) {
                                v.context.startActivity(whatsappIntent)
                            } else {
                                Toast.makeText(kosongActivity, "WhatsApp not installed", Toast.LENGTH_SHORT).show()
                            }
                        }
                    },3000)
                }else{
                    Toast.makeText(kosongActivity, "Diluar jam masuk tidak bisa diproses", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(kosongActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
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
        val queue = Volley.newRequestQueue(kosongActivity)
        queue.add(request)
    }

    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""

    private fun kirimNotifikasi(context: Context, kodePeserta: String){
        val client = AsyncHttpClient()
        val params = RequestParams()
        preferences = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        params.put("id", preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
        params.put("peserta", kodePeserta)
        params.put("pilihan", "notifikasiPeserta")
        params.put("event", "notifikasiPeserta")

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
                            }else if(item["tanggal"]?.toLowerCase()?.contains(filterPattern) == true){
                                filteredDataList.add(item)
                                break
                            }else if(item["namaKmps"]?.toLowerCase()?.contains(filterPattern) == true){
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
                    kosongActivity.notFound()
                }else{
                    kosongActivity.found()
                }
                notifyDataSetChanged()
            }
        }
    }
}