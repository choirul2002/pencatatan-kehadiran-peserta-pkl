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
import kotlinx.android.synthetic.main.activity_logpos_peserta.*
import org.json.JSONObject
import java.util.*
import kotlin.collections.HashMap

class AdapterLogpos(val dataForum:List<HashMap<String,String>>, val logPosActivity: LogPosActivity) : RecyclerView.Adapter<AdapterLogpos.HolderDataForum>(),
    Filterable {
    private var filteredList: List<HashMap<String, String>> = dataForum

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterLogpos.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_logpos,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterLogpos.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        holder.peserta.setText(data.get("namaMhs").toString())
        holder.kampus.setText(data.get("namaKmps").toString())
        holder.tanggal.setText(data.get("tanggal").toString())
        Picasso.get().load(data.get("profil")).into(holder.foto)

        holder.card.setOnLongClickListener(View.OnLongClickListener(){
            kosong()
        })

        holder.card.setOnClickListener {
            informasi(data.get("keterangan").toString())
        }

        holder.foto.setOnClickListener {
            logPosActivity.lihat = "1"
            logPosActivity.searchItem.isVisible = false
            logPosActivity.micItem.isVisible = false
            logPosActivity.supportActionBar?.setTitle(data.get("namaMhs").toString())
            logPosActivity.supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(Color.BLACK))
            }

            Picasso.get().load(data.get("profil")).into(logPosActivity.imageView19)

            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    logPosActivity.imageView19.scaleX = value
                    logPosActivity.imageView19.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(logPosActivity.fLayout, "alpha", 0f, 1f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
            }

            logPosActivity.fLayout.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                        logPosActivity.imageView19.scaleX = 0f
                        logPosActivity.imageView19.scaleY = 0f
                    }
                }
            })

            logPosActivity.window.statusBarColor = ContextCompat.getColor(logPosActivity, R.color.black)

            logPosActivity.fLayout.visibility = View.VISIBLE
            animatorSet.start()
        }
    }

    private fun kosong(): Boolean{
        return true
    }

    @SuppressLint("MissingInflatedId")
    private fun informasi(keterangan:String): Boolean {
        val inflater = LayoutInflater.from(logPosActivity)
        val dialogView = inflater.inflate(R.layout.custom_dialog,null)
        val informasi = dialogView.findViewById<TextView>(R.id.textView127)
        val kembali = dialogView.findViewById<TextView>(R.id.textView128)

        kembali.setText("Kembali")
        informasi.setText(keterangan)

        kembali.setOnClickListener{
            logPosActivity.alertDialog.dismiss()
        }

        val builder = android.app.AlertDialog.Builder(logPosActivity)
        builder.setView(dialogView)
        logPosActivity.alertDialog = builder.create()
        logPosActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
        logPosActivity.alertDialog.show()

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
                    logPosActivity.notFound()
                }else{
                    logPosActivity.found()
                }
                notifyDataSetChanged()
            }
        }
    }
}