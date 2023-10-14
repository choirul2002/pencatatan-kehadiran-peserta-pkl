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
import kotlinx.android.synthetic.main.row_nama.view.*

class AdapterNama(private val dataList: ArrayList<DataResponse>) : RecyclerView.Adapter<AdapterNama.PostViewHolder>() {

    inner class PostViewHolder(itemView:View):RecyclerView.ViewHolder(itemView){
        fun bind(dataResponse: DataResponse){
            with(itemView){
//                val text1 = "id: ${dataResponse.id}"
//                val text2 = "title: ${dataResponse.title}"
//                val text3 = "body: ${dataResponse.body}"
//                textView79.text = text1
//                textView170.text = text2
//                textView171.text = text3

                val text1 = "nama: ${dataResponse.nama}"
                textView79.text = text1
            }
        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): PostViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.row_nama, parent, false)
        return PostViewHolder(view)
    }

    override fun onBindViewHolder(holder: PostViewHolder, position: Int) {
        holder.bind(dataList[position])
    }

    override fun getItemCount(): Int = dataList.size
}