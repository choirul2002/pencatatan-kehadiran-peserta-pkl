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
import org.json.JSONObject
import org.w3c.dom.Text

class AdapterAsalPeserta(val dataForum:List<HashMap<String,String>>, val asalPeserta: AsalPesertaActivity) : RecyclerView.Adapter<AdapterAsalPeserta.HolderDataForum>(), Filterable{
    private var filteredList: List<HashMap<String, String>> = dataForum

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterAsalPeserta.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_asal_peserta,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterAsalPeserta.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        holder.nama.setText(data.get("nama").toString())
        holder.card.setOnLongClickListener(View.OnLongClickListener(){
            kosong()
        })

        holder.card.setOnClickListener {
            informasi(data.get("nama").toString(), data.get("tlp").toString(), data.get("fax").toString(), data.get("alamat").toString(), data.get("website").toString())
        }
    }

    private fun kosong(): Boolean{
        return true
    }

    private fun informasi(namaf:String, tlpf:String, faxf:String, alamatf:String, websitef:String): Boolean {
            val inflater = asalPeserta.layoutInflater
            val dialogView = inflater.inflate(R.layout.informasi_asal,null)
            val kembali = dialogView.findViewById<TextView>(R.id.informasiKembali)
            val nama = dialogView.findViewById<TextView>(R.id.textView82)
            val telp = dialogView.findViewById<TextView>(R.id.textView108)
            val fax = dialogView.findViewById<TextView>(R.id.textView110)
            val alamat = dialogView.findViewById<TextView>(R.id.textView112)
            val website = dialogView.findViewById<TextView>(R.id.textView120)

            nama.setText(namaf)
            telp.setText(tlpf)
            fax.setText(faxf)
            alamat.setText(alamatf)
            website.setText(websitef)
            kembali.setText("Kembali")

            kembali.setOnClickListener {
                asalPeserta.isDialog.dismiss()
            }
            website.setOnClickListener {
                asalPeserta.isDialog.dismiss()
                asalPeserta.startLoading()
                val handler = Handler()
                handler.postDelayed(object:Runnable{
                    override fun run() {
                        asalPeserta.isDismiss()
                        val url = websitef // Replace with the URL of the website you want to open
                        val intent = Intent(Intent.ACTION_VIEW, Uri.parse(url))
                        asalPeserta.startActivity(intent)
                    }
                },3000)
            }

            val builder = android.app.AlertDialog.Builder(asalPeserta)
            builder.setView(dialogView)
            asalPeserta.isDialog = builder.create()
            asalPeserta.isDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
            asalPeserta.isDialog.show()

        return false
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val nama = v.findViewById<TextView>(R.id.textViewNama)
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
                            if (item["nama"]?.toLowerCase()?.contains(filterPattern) == true) {
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
                    asalPeserta.notFound()
                }else{
                    asalPeserta.found()
                }
                notifyDataSetChanged()
            }
        }
    }
}