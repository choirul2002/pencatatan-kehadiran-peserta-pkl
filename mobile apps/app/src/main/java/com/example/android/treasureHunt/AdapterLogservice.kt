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
import kotlinx.android.synthetic.main.activity_logpos_peserta.*
import org.json.JSONObject
import java.util.*
import kotlin.collections.HashMap

class AdapterLogservice(val dataForum:List<HashMap<String,String>>, val logServiceActivity: LogServiceActivity) : RecyclerView.Adapter<AdapterLogservice.HolderDataForum>(),
    Filterable {
    private var filteredList: List<HashMap<String, String>> = dataForum
    val absen = "http://192.168.43.57/simaptapkl/public/service/absensi.php"

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterLogservice.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_logpos,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterLogservice.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        holder.peserta.setText(data.get("namaMhs").toString())
        holder.kampus.setText(data.get("namaKmps").toString())
        holder.tanggal.setText(data.get("tanggal").toString())
        Picasso.get().load(data.get("profil")).into(holder.foto)

        holder.card.setOnLongClickListener(View.OnLongClickListener(){
            informasi(data.get("keterangan").toString())
        })

        holder.card.setOnClickListener {
            popupMenus(it, data.get("wa").toString())
        }

        holder.foto.setOnClickListener {
            logServiceActivity.lihat = "1"
            logServiceActivity.searchItem.isVisible = false
            logServiceActivity.micItem.isVisible = false
            logServiceActivity.supportActionBar?.setTitle(data.get("namaMhs").toString())
            logServiceActivity.supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(Color.BLACK))
            }

            Picasso.get().load(data.get("profil")).into(logServiceActivity.imageView19)

            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    logServiceActivity.imageView19.scaleX = value
                    logServiceActivity.imageView19.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(logServiceActivity.fLayout, "alpha", 0f, 1f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
            }

            logServiceActivity.fLayout.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                        logServiceActivity.imageView19.scaleX = 0f
                        logServiceActivity.imageView19.scaleY = 0f
                    }
                }
            })

            logServiceActivity.window.statusBarColor = ContextCompat.getColor(logServiceActivity, R.color.black)

            logServiceActivity.fLayout.visibility = View.VISIBLE
            animatorSet.start()
        }
    }

    private fun popupMenus(v: View, wa:String): Boolean {
        val popupMenus = PopupMenu(logServiceActivity,v)
        popupMenus.inflate(R.menu.menu_popup_service)
        popupMenus.setOnMenuItemClickListener {
            when(it.itemId){
                R.id.itemWhatshapp->{
                    cekjamkerjawhatshapp(logServiceActivity, wa, v)
                    true
                }
                else->true
            }
        }

        popupMenus.show()

        return false
    }

    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""

    fun cekjamkerjawhatshapp(context: Context, wa: String, v: View){
        val request = object : StringRequest(
            Method.POST,absen,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(logServiceActivity)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            val packageManager = logServiceActivity.packageManager
                            val whatsappIntent = Intent(Intent.ACTION_SENDTO, Uri.parse("smsto:" + wa + "@s.whatsapp.net"))
                            whatsappIntent.setPackage("com.whatsapp")
                            if (whatsappIntent.resolveActivity(packageManager) != null) {
                                v.context.startActivity(whatsappIntent)
                            } else {
                                Toast.makeText(logServiceActivity, "WhatsApp not installed", Toast.LENGTH_SHORT).show()
                            }
                        }
                    },3000)
                }else{
                    Toast.makeText(logServiceActivity, "Diluar jam masuk tidak bisa diproses", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(logServiceActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
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
        val queue = Volley.newRequestQueue(logServiceActivity)
        queue.add(request)
    }

    @SuppressLint("MissingInflatedId")
    private fun informasi(keterangan:String): Boolean {
        val inflater = LayoutInflater.from(logServiceActivity)
        val dialogView = inflater.inflate(R.layout.custom_dialog,null)
        val informasi = dialogView.findViewById<TextView>(R.id.textView127)
        val kembali = dialogView.findViewById<TextView>(R.id.textView128)

        kembali.setText("Kembali")
        informasi.setText(keterangan)

        kembali.setOnClickListener{
            logServiceActivity.alertDialog.dismiss()
        }

        val builder = android.app.AlertDialog.Builder(logServiceActivity)
        builder.setView(dialogView)
        logServiceActivity.alertDialog = builder.create()
        logServiceActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
        logServiceActivity.alertDialog.show()

        return false
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val foto = v.findViewById<CircleImageView>(R.id.profile_image)
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
                    logServiceActivity.notFound()
                }else{
                    logServiceActivity.found()
                }
                notifyDataSetChanged()
            }
        }
    }
}