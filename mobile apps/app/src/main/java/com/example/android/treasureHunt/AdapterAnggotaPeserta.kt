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
import com.squareup.picasso.Picasso
import de.hdodenhof.circleimageview.CircleImageView
import kotlinx.android.synthetic.main.activity_tim_peserta.*
import org.json.JSONObject
import org.w3c.dom.Text

class AdapterAnggotaPeserta(val dataForum:List<HashMap<String,String>>, val timPesertaActivity: TimPesertaActivity) : RecyclerView.Adapter<AdapterAnggotaPeserta.HolderDataForum>(){
    private var filteredList: List<HashMap<String, String>> = dataForum
    val anggota = "http://192.168.43.57/simaptapkl/public/service/timPeserta.php"
    lateinit var preferences: SharedPreferences
    val PREF_NAME = "akun"
    val ID_AKUN = "id"
    val DEF_ID_AKUN = ""

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterAnggotaPeserta.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_tim_peserta,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterAnggotaPeserta.HolderDataForum, position: Int) {
        val data = filteredList.get(position)
        holder.nama.setText(data.get("nama").toString())
        Picasso.get().load(data.get("foto").toString()).into(holder.fotoProfil)
        holder.card.setOnClickListener {
            timPesertaActivity.dialog.dismiss()
            akun(data.get("mahasiswa").toString())
        }

        holder.fotoProfil.setOnClickListener {
            timPesertaActivity.dialog.dismiss()
            timPesertaActivity.lihat = "1"
            timPesertaActivity.searchItem.isVisible = false
            timPesertaActivity.micItem.isVisible = false
            timPesertaActivity.supportActionBar?.setTitle(data.get("nama").toString())
            timPesertaActivity.supportActionBar?.apply {
                setBackgroundDrawable(ColorDrawable(Color.BLACK))
            }

            Picasso.get().load(data.get("foto")).into(timPesertaActivity.imageView19)

            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    timPesertaActivity.imageView19.scaleX = value
                    timPesertaActivity.imageView19.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(timPesertaActivity.fLayout, "alpha", 0f, 1f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
            }

            timPesertaActivity.fLayout.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                        timPesertaActivity.imageView19.scaleX = 0f
                        timPesertaActivity.imageView19.scaleY = 0f
                    }
                }
            })

            timPesertaActivity.window.statusBarColor = ContextCompat.getColor(timPesertaActivity, R.color.black)

            timPesertaActivity.fLayout.visibility = View.VISIBLE
            animatorSet.start()
        }
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val nama = v.findViewById<TextView>(R.id.textViewNama)
        val fotoProfil = v.findViewById<CircleImageView>(R.id.profile_image)
        val card = v.findViewById<CardView>(R.id.cardView11)
    }

    fun akun(kode:String){
        val request = object : StringRequest(
            Method.POST,anggota,
            Response.Listener { response ->
                val jsonObject = JSONObject(response)
                val fnama = jsonObject.getString("NAMA_PST")
                val fkelamin = jsonObject.getString("JK_PST")
                val fwa = jsonObject.getString("NOHP_PST")
                val fagama = jsonObject.getString("AGAMA_PST")
                val falamat = jsonObject.getString("ALAMAT_PST")
                val femail = jsonObject.getString("EMAIL")

                val inflater = timPesertaActivity.layoutInflater
                val dialogView = inflater.inflate(R.layout.informasi_person,null)
                val kembali = dialogView.findViewById<TextView>(R.id.informasiKembali)
                val nama = dialogView.findViewById<TextView>(R.id.textView82)
                val kelamin = dialogView.findViewById<TextView>(R.id.textView105)
                val agama = dialogView.findViewById<TextView>(R.id.textView110)
                val alamat = dialogView.findViewById<TextView>(R.id.textView112)
                val whatshap = dialogView.findViewById<TextView>(R.id.textView108)
                val email = dialogView.findViewById<TextView>(R.id.textView120)

                nama.setText(fnama)
                kelamin.setText(fkelamin)
                agama.setText(fagama)
                alamat.setText(falamat)
                whatshap.setText(fwa)
                email.setText(femail)
                kembali.setText("Kembali")

                kembali.setOnClickListener {
                    timPesertaActivity.isDialog.dismiss()
                }

                email.setOnClickListener {v : View ->
                    timPesertaActivity.isDialog.dismiss()
                    timPesertaActivity.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            timPesertaActivity.isDismiss()
                            val intent = Intent(Intent.ACTION_SENDTO)
                            intent.data = Uri.parse("mailto:") // hanya aplikasi email yang akan menangani intent ini
                            intent.putExtra(Intent.EXTRA_EMAIL, arrayOf(femail)) // tambahkan alamat email penerima
                            intent.putExtra(Intent.EXTRA_SUBJECT, "Subject of the email") // tambahkan subjek email
                            if (intent.resolveActivity(timPesertaActivity.packageManager) != null) {
                                v.context.startActivity(intent)
                            } else {
                                Toast.makeText(timPesertaActivity, "No email app found", Toast.LENGTH_SHORT).show()
                            }
                        }
                    },3000)
                }

                whatshap.setOnClickListener {v : View ->
                    timPesertaActivity.isDialog.dismiss()
                    timPesertaActivity.startLoading()
                    val handler = Handler()
                    handler.postDelayed(object:Runnable{
                        override fun run() {
                            timPesertaActivity.isDismiss()
                            val packageManager = timPesertaActivity.packageManager
                            val whatsappIntent = Intent(Intent.ACTION_SENDTO, Uri.parse("smsto:" + fwa + "@s.whatsapp.net"))
                            whatsappIntent.setPackage("com.whatsapp")
                            if (whatsappIntent.resolveActivity(packageManager) != null) {
                                v.context.startActivity(whatsappIntent)
                            } else {
                                Toast.makeText(timPesertaActivity, "WhatsApp not installed", Toast.LENGTH_SHORT).show()
                            }
                        }
                    },3000)
                }

                val builder = android.app.AlertDialog.Builder(timPesertaActivity)
                builder.setView(dialogView)
                timPesertaActivity.isDialog = builder.create()
                timPesertaActivity.isDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
                timPesertaActivity.isDialog.show()
            },
            Response.ErrorListener { error ->
                Toast.makeText(timPesertaActivity,"Koneksi terputus", Toast.LENGTH_LONG).show()
            }
        ){
            override fun getParams(): MutableMap<String, String>? {
                preferences = timPesertaActivity.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
                val data = java.util.HashMap<String, String>()
                data.put("pilihan","dataDetailAnggota")
                data.put("id",preferences.getString(ID_AKUN,DEF_ID_AKUN).toString())
                data.put("mahasiswa",kode)

                return data
            }
        }
        val queue = Volley.newRequestQueue(timPesertaActivity)
        queue.add(request)
    }
}