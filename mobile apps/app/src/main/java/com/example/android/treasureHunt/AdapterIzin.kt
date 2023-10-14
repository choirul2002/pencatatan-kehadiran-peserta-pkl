package com.example.android.treasureHunt

import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.annotation.SuppressLint
import android.content.DialogInterface
import android.content.Intent
import android.graphics.Color
import android.graphics.drawable.ColorDrawable
import android.os.Handler
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.view.Window
import android.widget.*
import androidx.appcompat.app.AlertDialog
import androidx.cardview.widget.CardView
import androidx.core.content.ContextCompat
import androidx.core.view.isVisible
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.bottomsheet.BottomSheetDialog
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_izin.*
import org.json.JSONObject

class AdapterIzin(val dataForum:List<HashMap<String,String>>,val izinActivity: IzinActivity) : RecyclerView.Adapter<AdapterIzin.HolderDataForum>(),
    Filterable {
    private var filteredList: List<HashMap<String, String>> = dataForum

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterIzin.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_izin,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterIzin.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        if(data.get("status_surat").equals("approve")){
            holder.status.setText("Approve")
            holder.status.setTextColor(Color.parseColor("#3AB24A"))
        }else if(data.get("status_surat").equals("disapprove")){
            holder.status.setText("Disapprove")
            holder.status.setTextColor(Color.parseColor("#FF0000"))
        }else{
            holder.status.setText("Waiting")
            holder.status.setTextColor(Color.parseColor("#FF5722"))
        }

        holder.keterangan.setText(data.get("keterangan"))
        holder.tanggal.setText(data.get("tanggal"))
        holder.pop.setOnLongClickListener(View.OnLongClickListener(){
            popupMenus(it,data.get("id").toString(), data.get("status_surat").toString())
        })

        holder.pop.setOnClickListener { v : View ->
            izinActivity.lihat = "1"
            izinActivity.supportActionBar?.setTitle("Foto Surat")
            izinActivity.supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(Color.BLACK))
            }

            izinActivity.searchItem.isVisible = false
            izinActivity.micItem.isVisible = false
            Picasso.get().load(data.get("surat")).into(izinActivity.imageView16)

            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    izinActivity.imageView16.scaleX = value
                    izinActivity.imageView16.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(izinActivity.flSurat, "alpha", 0f, 1f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
            }

            izinActivity.flSurat.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                        izinActivity.imageView16.scaleX = 0f
                        izinActivity.imageView16.scaleY = 0f
                    }
                }
            })

            izinActivity.window.statusBarColor = ContextCompat.getColor(izinActivity, R.color.black)

            izinActivity.flSurat.visibility = View.VISIBLE
            animatorSet.start()
        }
    }

    private fun popupMenus(v: View,kode: String, sts:String): Boolean {
        val popupMenus = PopupMenu(izinActivity,v)
        popupMenus.inflate(R.menu.menu_popup)
        popupMenus.setOnMenuItemClickListener {
            when(it.itemId){
                R.id.itemEdit->{
                    if(sts.equals("approve")){
                        Toast.makeText(izinActivity, "Status approve tidak bisa diedit",Toast.LENGTH_LONG).show()
                    }else if(sts.equals("disapprove")){
                        val dialogView = LayoutInflater.from(izinActivity).inflate(R.layout.bottom_sheet_surat_izin, null)
                        val storage = dialogView.findViewById<CircleImageView>(R.id.vector)
                        val kamera = dialogView.findViewById<CircleImageView>(R.id.camear)

                        storage.setOnClickListener {
                            val intent = Intent()
                            intent.setType("image/*")
                            intent.setAction(Intent.ACTION_GET_CONTENT)
                            izinActivity.startActivityForResult(intent,kode.toInt())
                            izinActivity.jenisEdit = "galeri"
                            izinActivity.dialog.dismiss()
                        }

                        kamera.setOnClickListener {
                            izinActivity.dispatchTakePictureIntentEdit(kode.toInt())
                            izinActivity.jenisEdit = "kamera"
                            izinActivity.dialog.dismiss()
                        }

                        izinActivity.dialog = BottomSheetDialog(izinActivity, R.style.BottomSheetDialogTheme)
                        izinActivity.dialog.setContentView(dialogView)
                        izinActivity.dialog.show()
                    }else{
                        Toast.makeText(izinActivity, "Izin masih dalam proses",Toast.LENGTH_LONG).show()
                    }
                    true
                }
                R.id.itemHapus->{
                    if(sts.equals("approve")){
                        Toast.makeText(izinActivity, "Status approve tidak bisa dihapus",Toast.LENGTH_LONG).show()
                    }else if(sts.equals("disapprove")){
                        val inflater = LayoutInflater.from(izinActivity)
                        val dialogView = inflater.inflate(R.layout.custom_dialog,null)
                        val informasi = dialogView.findViewById<TextView>(R.id.textView127)
                        val kirim = dialogView.findViewById<TextView>(R.id.textView128)
                        val kembali = dialogView.findViewById<TextView>(R.id.textView129)

                        kembali.setText("Kembali")
                        kirim.setText("Hapus")
                        informasi.setText("Apakah anda yakin ingin menghapus surat izin ini ?")

                        kembali.setOnClickListener{
                            izinActivity.alertDialog.dismiss()
                        }

                        kirim.setOnClickListener{
                            izinActivity.alertDialog.dismiss()
                            hapus(kode)
                        }

                        val builder = android.app.AlertDialog.Builder(izinActivity)
                        builder.setView(dialogView)
                        izinActivity.alertDialog = builder.create()
                        izinActivity.alertDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                        izinActivity.alertDialog.show()
                    }else{
                        Toast.makeText(izinActivity, "Izin masih dalam proses",Toast.LENGTH_LONG).show()
                    }
                    true
                }
                else->true
            }
        }

        popupMenus.show()

        return false
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val status = v.findViewById<TextView>(R.id.textView35)
        val keterangan = v.findViewById<TextView>(R.id.textView36)
        val tanggal = v.findViewById<TextView>(R.id.textView53)
        val pop = v.findViewById<CardView>(R.id.cardView11)
    }

    val perizinan = "http://192.168.43.57/simaptapkl/public/service/perizinan.php"
    private fun hapus(kode : String){
        val request = object : StringRequest(
            Method.POST,perizinan,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val respon = jsonObject.getString("respon")

                if(respon.equals("1")){
                    val loading = LoadingDialog(izinActivity)
                    loading.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            loading.isDismiss()
                            izinActivity.dataIzin()
                            izinActivity.cekIzin()
                            izinActivity.jumlahIzinWaiting()
                            Toast.makeText(izinActivity,"Berhasil menghapus surat izin",Toast.LENGTH_LONG).show()
                        }
                    },3000)
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(izinActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }){
            override fun getParams(): MutableMap<String, String>? {
                val data = HashMap<String,String>()

                data.put("pilihan","hapus")
                data.put("id","")
                data.put("id_absen",kode)

                return data
            }
        }
        val  queue = Volley.newRequestQueue(izinActivity)
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
                            if (item["status_surat"]?.toLowerCase()?.contains(filterPattern) == true) {
                                filteredDataList.add(item)
                                break
                            }else if(item["tanggal"]?.toLowerCase()?.contains(filterPattern) == true){
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
                    izinActivity.notFound()
                }else{
                    izinActivity.found()
                }
                notifyDataSetChanged()
            }
        }
    }
}