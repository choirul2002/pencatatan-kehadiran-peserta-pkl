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
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.bottomsheet.BottomSheetDialog
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import org.json.JSONArray
import org.json.JSONObject
import org.w3c.dom.Text

class AdapterTimPesertaPembimbing(val dataForum:List<HashMap<String,String>>, val timPesertaActivity: TimPesertaActivity) : RecyclerView.Adapter<AdapterTimPesertaPembimbing.HolderDataForum>(), Filterable{
    private var filteredList: List<HashMap<String, String>> = dataForum
    val anggota = "http://192.168.43.57/simaptapkl/public/service/timPeserta.php"
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterTimPesertaPembimbing.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_timpeserta_pembimbing,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterTimPesertaPembimbing.HolderDataForum, position: Int) {
        val data = filteredList.get(position)
        holder.namaTim.setText(data.get("tim").toString())
        holder.namaKampus.setText(data.get("kampus").toString())
        holder.tahun.setText(data.get("tahun").toString())
        holder.card.setOnClickListener {
            dataAnggotaPeserta(data.get("kode").toString())
            val dialogView = timPesertaActivity.layoutInflater.inflate(R.layout.bottom_sheet_tim_peserta, null)
            val formMulai = dialogView.findViewById<TextView>(R.id.textView148)
            val formSelesai = dialogView.findViewById<TextView>(R.id.textView151)
            val recycler = dialogView.findViewById<RecyclerView>(R.id.lsForumBottomSheetDialog)

            formMulai.setText(data.get("mulai").toString())
            formSelesai.setText(data.get("selesai").toString())

            recycler.layoutManager = LinearLayoutManager(timPesertaActivity)
            recycler.adapter = timPesertaActivity.forumAnggota
            timPesertaActivity.dialog = BottomSheetDialog(timPesertaActivity, R.style.BottomSheetDialogTheme)
            timPesertaActivity.dialog.setContentView(dialogView)
            timPesertaActivity.dialog.show()
        }
        holder.card.setOnLongClickListener(View.OnLongClickListener(){
//            informasi(it, timPesertaActivity, data.get("mulai").toString(), data.get("selesai").toString())
            kosong()
        })
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val namaTim = v.findViewById<TextView>(R.id.textView79)
        val namaKampus = v.findViewById<TextView>(R.id.textView35)
        val tahun = v.findViewById<TextView>(R.id.textView46)
        val card = v.findViewById<CardView>(R.id.cardView11)
    }

    private fun kosong(): Boolean{
        return true
    }

    private fun informasi(v:View, context: Context, mulai:String, selesai:String): Boolean {
        val inflater = timPesertaActivity.layoutInflater
        val dialogView = inflater.inflate(R.layout.activity_form_informasi_masa_pkl,null)
        val fmulai = dialogView.findViewById<TextView>(R.id.textView41)
        val fselesai = dialogView.findViewById<TextView>(R.id.textView48)
        val kembali = dialogView.findViewById<TextView>(R.id.textView128)

        fmulai.setText(mulai)
        fselesai.setText(selesai)

        kembali.setText("Kembali")

        kembali.setOnClickListener{
            timPesertaActivity.alertDialog.dismiss()
        }

        val builder = android.app.AlertDialog.Builder(timPesertaActivity)
        builder.setView(dialogView)
        timPesertaActivity.alertDialog = builder.create()
        timPesertaActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
        timPesertaActivity.alertDialog.show()

        return false
    }

    fun dataAnggotaPeserta(kode:String){
        val request = object : StringRequest(
            Method.POST,anggota,
            Response.Listener { response ->
                timPesertaActivity.daftarForumAnggota.clear()
                val jsonArray = JSONArray(response)
                for (x in 0..(jsonArray.length()-1)){
                    val jsonObject = jsonArray.getJSONObject(x)
                    var  frm = HashMap<String,String>()
                    frm.put("nama",jsonObject.getString("NAMA_PST"))
                    frm.put("foto",jsonObject.getString("url"))
                    frm.put("mahasiswa",jsonObject.getString("KD_PST"))

                    timPesertaActivity.daftarForumAnggota.add(frm)
                }
                timPesertaActivity.forumAnggota.notifyDataSetChanged()
            },
            Response.ErrorListener { error ->
                Toast.makeText(timPesertaActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()
                preferences = timPesertaActivity.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                data.put("pilihan","dataAnggota")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("tim",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(timPesertaActivity)
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
                            if (item["tim"]?.toLowerCase()?.contains(filterPattern) == true) {
                                filteredDataList.add(item)
                                break
                            }else if(item["kampus"]?.toLowerCase()?.contains(filterPattern) == true){
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
                    timPesertaActivity.notFound()
                }else{
                    timPesertaActivity.found()
                }
                notifyDataSetChanged()
            }
        }
    }
}