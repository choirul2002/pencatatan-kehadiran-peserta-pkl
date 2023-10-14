package com.example.android.treasureHunt

import android.app.NotificationChannel
import android.app.NotificationManager
import android.content.Context
import android.content.Intent
import android.graphics.Color
import android.os.Build
import android.os.Bundle
import android.util.Log
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.NotificationCompat
import androidx.core.app.NotificationManagerCompat
import kotlinx.android.synthetic.main.coba5.*
import java.util.*
import android.app.ActivityManager
import android.app.ActivityManager.RunningAppProcessInfo
import android.view.*
import android.view.inputmethod.EditorInfo
import android.widget.*
import androidx.core.content.ContextCompat

class CustomAutoCompleteAdapter(context: Context, items: Array<Coba5.Item>) :
    ArrayAdapter<Coba5.Item>(context, 0, items) {

    override fun getView(position: Int, convertView: View?, parent: ViewGroup): View {
        var itemView = convertView
        val viewHolder: ViewHolder

        if (itemView == null) {
            viewHolder = ViewHolder()
            itemView = LayoutInflater.from(context).inflate(R.layout.item_person, parent, false)
//            viewHolder.imageView = itemView.findViewById(R.id.photoImageView)
            viewHolder.textView = itemView.findViewById(R.id.nameTextView)
            itemView.tag = viewHolder
        } else {
            viewHolder = itemView.tag as ViewHolder
        }

        val item = getItem(position)
        viewHolder.textView?.text = item?.nama

        // Load the image using your preferred image loading library (e.g., Picasso, Glide)
        // Picasso.with(context).load(item?.imageUrl).into(viewHolder.imageView)
        // or
        // Glide.with(context).load(item?.imageUrl).into(viewHolder.imageView)

        return itemView!!
    }

    private class ViewHolder {
//        var imageView: ImageView? = null
        var textView: TextView? = null
    }
}

class Coba5: AppCompatActivity(),View.OnClickListener {
    data class Item(val kd_nama: String, val nama: String) {
        override fun toString(): String {
            return nama
        }
    }
    private val items = arrayOf(
        Item("1", "Apple"),
        Item("2", "aanana"),
        Item("3", "aherry"),
        Item("4", "bate"),
        Item("5", "blderberry"),
        Item("6", "big"),
        Item("7", "brapes")
    )

    override fun onClick(v: View?) {
        when(v?.id){
            R.id.button11->{

            }
            R.id.button12->{

            }
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.coba5)
        window.statusBarColor = ContextCompat.getColor(this, R.color.statusBarColor)

        val service = Intent(this, PembimbingService::class.java)
        startService(service)
    }

    override fun onStop() {
        super.onStop()
        Toast.makeText(this, "aplikasi stop", Toast.LENGTH_SHORT).show()
    }

    override fun onDestroy() {
        super.onDestroy()
        Toast.makeText(this, "aplikasi destroy", Toast.LENGTH_SHORT).show()
    }
}