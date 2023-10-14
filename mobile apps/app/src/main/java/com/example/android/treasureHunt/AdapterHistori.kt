package com.example.android.treasureHunt

import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.annotation.SuppressLint
import android.content.DialogInterface
import android.graphics.Color
import android.graphics.drawable.ColorDrawable
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.*
import androidx.appcompat.app.AlertDialog
import androidx.cardview.widget.CardView
import androidx.core.content.ContextCompat
import androidx.core.view.isVisible
import androidx.recyclerview.widget.RecyclerView
import com.google.android.material.textfield.TextInputEditText
import com.squareup.picasso.Picasso
import kotlinx.android.synthetic.main.activity_histori.*

class AdapterHistori(val dataForum:List<HashMap<String,String>>,val historiActivity: HistoriActivity) : RecyclerView.Adapter<AdapterHistori.HolderDataForum>(),
    Filterable {
    private var filteredList: List<HashMap<String, String>> = dataForum

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_histori,parent,false)
        return  HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    override fun onBindViewHolder(holder: AdapterHistori.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        if(data.get("status").equals("hadir")){
            holder.status.setText("Hadir")
            holder.status.setTextColor(Color.parseColor("#3AB24A"))
            holder.namaKondisi.setText("Kegiatan")
            holder.textKondisi.setText(data.get("kegiatan").toString())
            if(data.get("kegiatan").toString().equals("")){
                holder.textKondisi.setText("-")
            }else{
                holder.textKondisi.setText(data.get("kegiatan").toString())
            }
        }else{
            holder.status.setText("Izin")
            holder.status.setTextColor(Color.parseColor("#FF0000"))
            holder.namaKondisi.setText("Keterangan")
            holder.textKondisi.setText(data.get("keterangan"))
        }

        holder.card.setOnClickListener {
            if(data.get("status").equals("izin")){
                historiActivity.lihat = "1"
                historiActivity.supportActionBar?.setTitle("Foto Surat")
                historiActivity.supportActionBar?.apply {
                    setBackgroundDrawable(ColorDrawable(Color.BLACK))
                }

                historiActivity.searchItem.isVisible = false
                historiActivity.micItem.isVisible = false
                historiActivity.absensiItem.isVisible = false
                Picasso.get().load(data.get("surat")).into(historiActivity.imageView19)

                val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                    duration = 250
                    addUpdateListener {
                        val value = it.animatedValue as Float
                        historiActivity.imageView19.scaleX = value
                        historiActivity.imageView19.scaleY = value
                    }
                }

                val visibilityAnimator = ObjectAnimator.ofFloat(historiActivity.flSurat, "alpha", 0f, 1f).apply {
                    duration = 250
                }

                val animatorSet = AnimatorSet().apply {
                    playTogether(animator, visibilityAnimator)
                }

                historiActivity.flSurat.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                            historiActivity.imageView19.scaleX = 0f
                            historiActivity.imageView19.scaleY = 0f
                        }
                    }
                })

                historiActivity.window.statusBarColor = ContextCompat.getColor(historiActivity, R.color.black)

                historiActivity.flSurat.visibility = View.VISIBLE
                animatorSet.start()
            }else{
                val inflater = LayoutInflater.from(historiActivity)
                val dialogView = inflater.inflate(R.layout.activity_form_informasi,null)
                val kembali = dialogView.findViewById<TextView>(R.id.textView128)
                val formMasuk = dialogView.findViewById<TextView>(R.id.textView48)
                val lokasiMasuk = dialogView.findViewById<TextView>(R.id.textView47)
                val formPulang = dialogView.findViewById<TextView>(R.id.textView49)
                val lokasiPulang = dialogView.findViewById<TextView>(R.id.textView50)
                val formKehadiran = dialogView.findViewById<TextView>(R.id.textView41)

                if(data.get("kehadiran").toString().equals("tepat waktu")){
                    formKehadiran.setText("Tepat waktu")
                }else if(data.get("kehadiran").toString().equals("telat")){
                    formKehadiran.setText("Tepat waktu")
                }else{
                    formKehadiran.setText("Terlambat")
                }

                if(data.get("check_out").toString().equals("null")){
                    formPulang.setText("-")
                    lokasiPulang.setText("-")
                }else{
                    formPulang.setText(data.get("check_out").toString()+" WIB")
                    lokasiPulang.setText(data.get("lokasi_pulang").toString())
                }

                formMasuk.setText(data.get("check_in").toString()+" WIB")
                lokasiMasuk.setText(data.get("lokasi_masuk").toString())

                kembali.setText("Kembali")

                kembali.setOnClickListener{
                    historiActivity.alertDialog.dismiss()
                }

                val builder = android.app.AlertDialog.Builder(historiActivity)
                builder.setView(dialogView)
                historiActivity.alertDialog = builder.create()
                historiActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                historiActivity.alertDialog.show()
            }
        }


        holder.card.setOnLongClickListener(View.OnLongClickListener(){
            longClick(data.get("status").toString(),data.get("srt").toString(),data.get("lokSurat").toString())
        })

        holder.tanggal.setText(data.get("tanggal"))
    }

    @SuppressLint("MissingInflatedId")
    private fun longClick(status:String, surat:String, lokSurat:String): Boolean {
        if(status.equals("hadir")){

        }else{
//            val inflater = LayoutInflater.from(historiActivity)
//            val dialogView = inflater.inflate(R.layout.activity_form_informasi_izin,null)
//            val kembali = dialogView.findViewById<TextView>(R.id.textView128)
//            val lokasi = dialogView.findViewById<TextView>(R.id.textView47)
//            val formSurat = dialogView.findViewById<TextView>(R.id.textView41)
//
//            kembali.setText("Kembali")
//            formSurat.setText(surat)
//            lokasi.setText(lokSurat)
//
//            kembali.setOnClickListener{
//                historiActivity.alertDialog.dismiss()
//            }
//
//            val builder = android.app.AlertDialog.Builder(historiActivity)
//            builder.setView(dialogView)
//            historiActivity.alertDialog = builder.create()
//            historiActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
//            historiActivity.alertDialog.show()
        }

        return true
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val status = v.findViewById<TextView>(R.id.textView75)
        val textKondisi = v.findViewById<TextView>(R.id.textView35)
        val namaKondisi = v.findViewById<TextView>(R.id.textView19)
        val tanggal = v.findViewById<TextView>(R.id.textView53)
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
                            if (item["status"]?.toLowerCase()?.contains(filterPattern) == true) {
                                filteredDataList.add(item)
                                break
                            }else if(item["tanggal"]?.toLowerCase()?.contains(filterPattern) == true){
                                filteredDataList.add(item)
                                break
                            }else if(item["kehadiran"]?.toLowerCase()?.contains(filterPattern) == true){
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
                    historiActivity.notFound()
                }else{
                    historiActivity.found()
                }
                notifyDataSetChanged()
            }
        }
    }
}