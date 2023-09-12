package com.example.android.treasureHunt

import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.animation.ValueAnimator
import android.annotation.SuppressLint
import android.content.DialogInterface
import android.content.Intent
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
import kotlinx.android.synthetic.main.activity_edit_profil.*
import org.json.JSONObject
import org.w3c.dom.Text

class AdapterTimPeserta(val dataForum:List<HashMap<String,String>>, val editProfilActivity: EditProfilActivity) : RecyclerView.Adapter<AdapterTimPeserta.HolderDataForum>(){
    private var filteredList: List<HashMap<String, String>> = dataForum

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterTimPeserta.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_tim_peserta,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterTimPeserta.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        holder.nama.setText(data.get("nama_mhs").toString())
        Picasso.get().load(data.get("fotoProfil").toString()).into(holder.fotoProfil)
        holder.card.setOnClickListener {
            editProfilActivity.dialog.dismiss()

            val inflater = editProfilActivity.layoutInflater
            val dialogView = inflater.inflate(R.layout.informasi_person,null)
            val kembali = dialogView.findViewById<TextView>(R.id.informasiKembali)
            val nama = dialogView.findViewById<TextView>(R.id.textView82)
            val kelamin = dialogView.findViewById<TextView>(R.id.textView105)
            val agama = dialogView.findViewById<TextView>(R.id.textView110)
            val alamat = dialogView.findViewById<TextView>(R.id.textView112)
            val whatshap = dialogView.findViewById<TextView>(R.id.textView108)
            val email = dialogView.findViewById<TextView>(R.id.textView120)

            nama.setText(data.get("nama_mhs").toString())
            kelamin.setText(data.get("kelamin").toString())
            agama.setText(data.get("agama").toString())
            alamat.setText(data.get("alamat").toString())
            whatshap.setText(data.get("whatshap").toString())
            email.setText(data.get("email").toString())
            kembali.setText("Kembali")

            kembali.setOnClickListener {
                editProfilActivity.isDialog.dismiss()
            }

            email.setOnClickListener {v : View ->
                editProfilActivity.isDialog.dismiss()
                editProfilActivity.startLoading()
                val handler = Handler()
                handler.postDelayed(object:Runnable{
                    override fun run() {
                        editProfilActivity.isDismiss()
                        val intent = Intent(Intent.ACTION_SENDTO)
                        intent.data = Uri.parse("mailto:") // hanya aplikasi email yang akan menangani intent ini
                        intent.putExtra(Intent.EXTRA_EMAIL, arrayOf(data.get("email").toString())) // tambahkan alamat email penerima
                        intent.putExtra(Intent.EXTRA_SUBJECT, "Subject of the email") // tambahkan subjek email
                        if (intent.resolveActivity(editProfilActivity.packageManager) != null) {
                            v.context.startActivity(intent)
                        } else {
                            Toast.makeText(editProfilActivity, "No email app found", Toast.LENGTH_SHORT).show()
                        }
                    }
                },3000)
            }

            whatshap.setOnClickListener {v : View ->
                editProfilActivity.isDialog.dismiss()
                editProfilActivity.startLoading()
                val handler = Handler()
                handler.postDelayed(object:Runnable{
                    override fun run() {
                        editProfilActivity.isDismiss()
                        val packageManager = editProfilActivity.packageManager
                        val whatsappIntent = Intent(Intent.ACTION_SENDTO, Uri.parse("smsto:" + data.get("whatshap").toString() + "@s.whatsapp.net"))
                        whatsappIntent.setPackage("com.whatsapp")
                        if (whatsappIntent.resolveActivity(packageManager) != null) {
                            v.context.startActivity(whatsappIntent)
                        } else {
                            Toast.makeText(editProfilActivity, "WhatsApp not installed", Toast.LENGTH_SHORT).show()
                        }
                    }
                },3000)
            }

            val builder = android.app.AlertDialog.Builder(editProfilActivity)
            builder.setView(dialogView)
            editProfilActivity.isDialog = builder.create()
            editProfilActivity.isDialog.window?.setBackgroundDrawableResource(android.R.color.transparent)
            editProfilActivity.isDialog.show()
        }

        holder.fotoProfil.setOnClickListener {
            editProfilActivity.dialog.dismiss()
            editProfilActivity.lihat = "2"
            editProfilActivity.textView68.setText(data.get("nama_mhs").toString())
            editProfilActivity.imageView18.visibility = View.GONE
            Picasso.get().load(data.get("fotoProfil").toString()).into(editProfilActivity.imageView16)
            val animator = ValueAnimator.ofFloat(0f, 1f).apply {
                duration = 250
                addUpdateListener {
                    val value = it.animatedValue as Float
                    editProfilActivity.imageView16.scaleX = value
                    editProfilActivity.imageView16.scaleY = value
                }
            }

            val visibilityAnimator = ObjectAnimator.ofFloat(editProfilActivity.flFoto, "alpha", 0f, 1f).apply {
                duration = 250
            }

            val animatorSet = AnimatorSet().apply {
                playTogether(animator, visibilityAnimator)
            }

            editProfilActivity.flFoto.addOnLayoutChangeListener(object : View.OnLayoutChangeListener {
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
                        editProfilActivity.imageView16.scaleX = 0f
                        editProfilActivity.imageView16.scaleY = 0f
                    }
                }
            })

            editProfilActivity.window.statusBarColor = ContextCompat.getColor(editProfilActivity, R.color.black)
            editProfilActivity.window.decorView.systemUiVisibility = View.SYSTEM_UI_FLAG_VISIBLE

            editProfilActivity.flFoto.visibility = View.VISIBLE
            animatorSet.start()
        }
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val nama = v.findViewById<TextView>(R.id.textViewNama)
        val fotoProfil = v.findViewById<CircleImageView>(R.id.profile_image)
        val card = v.findViewById<CardView>(R.id.cardView11)
    }
}