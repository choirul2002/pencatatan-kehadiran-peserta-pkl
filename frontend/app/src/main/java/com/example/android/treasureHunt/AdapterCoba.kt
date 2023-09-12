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

class AdapterCoba(val dataForum:List<HashMap<String,String>>, val coba5: Coba5) : RecyclerView.Adapter<AdapterCoba.HolderDataForum>(){
    private var filteredList: List<HashMap<String, String>> = dataForum

    private val VIEW_TYPE_1 = 1
    private val VIEW_TYPE_2 = 2

    override fun getItemViewType(position: Int): Int {
        val nama = filteredList[position]["name"] as String
        return if (nama.equals("pembatas")) VIEW_TYPE_1 else VIEW_TYPE_2
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterCoba.HolderDataForum {
        return if (viewType == VIEW_TYPE_1) {
            val v = LayoutInflater.from(parent.context).inflate(R.layout.row_pembatas,parent,false)
            HolderDataForum(v)
        } else {
            val v = LayoutInflater.from(parent.context).inflate(R.layout.row_timpeserta_pembimbing,parent,false)
            HolderDataForum(v)
        }
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterCoba.HolderDataForum, position: Int) {
        val data = filteredList.get(position)

        val nama = filteredList[position]["name"] as String
        Toast.makeText(coba5, nama, Toast.LENGTH_SHORT).show()

        if(!nama.equals("pembatas")){
            holder.nama.setText(data.get("name").toString())
        }
    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val nama = v.findViewById<TextView>(R.id.textView79)
    }
}