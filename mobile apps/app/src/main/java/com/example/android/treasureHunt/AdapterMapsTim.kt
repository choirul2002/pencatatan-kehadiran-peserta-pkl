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
import kotlinx.android.synthetic.main.fragment_maps_pembimbing.*
import kotlinx.android.synthetic.main.fragment_maps_pembimbing.view.*
import org.json.JSONArray
import org.json.JSONObject
import org.w3c.dom.Text

class AdapterMapsTim(val dataForum:List<HashMap<String,String>>, val homePembimbingActivity: HomePembimbingActivity, val fragmentMapsPembimbing: FragmentMapsPembimbing) : RecyclerView.Adapter<AdapterMapsTim.HolderDataForum>(){
    private var filteredList: List<HashMap<String, String>> = dataForum

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AdapterMapsTim.HolderDataForum {
        val v = LayoutInflater.from(parent.context).inflate(R.layout.row_maps_tim,parent,false)
        return HolderDataForum(v)
    }

    override fun getItemCount(): Int {
        return filteredList.size
    }

    @SuppressLint("MissingInflatedId")
    override fun onBindViewHolder(holder: AdapterMapsTim.HolderDataForum, position: Int) {
        val data = filteredList.get(position)
        holder.nama.setText(data.get("tim").toString())

        holder.card.setOnClickListener {
            fragmentMapsPembimbing.flTim.visibility = View.GONE
            homePembimbingActivity.showBottomNavigationItemBeranda(R.id.btnBeranda)
            homePembimbingActivity.showBottomNavigationItemLokasi(R.id.btnLokasi)
            homePembimbingActivity.showBottomNavigationItemPengaturan(R.id.btnSistem)
            fragmentMapsPembimbing.cardView13.visibility = View.VISIBLE
            fragmentMapsPembimbing.btnRfsh.visibility = View.VISIBLE
            fragmentMapsPembimbing.kondisi = "tim"
            fragmentMapsPembimbing.dataTim = data.get("kode").toString()
            homePembimbingActivity.timer.cancel()
            fragmentMapsPembimbing.cekBerkala()
        }

        holder.card.setOnLongClickListener(View.OnLongClickListener(){
            kosong()
        })

    }

    class HolderDataForum(v : View) : RecyclerView.ViewHolder(v){
        val nama = v.findViewById<TextView>(R.id.textViewNama)
        val card = v.findViewById<CardView>(R.id.cardView11)
    }

    private fun kosong(): Boolean{
        return true
    }
}