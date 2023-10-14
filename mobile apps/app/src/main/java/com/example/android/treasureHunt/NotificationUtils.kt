/*
 * Copyright (C) 2019 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package com.example.android.treasureHunt

import android.app.Notification
import android.app.NotificationChannel
import android.app.NotificationManager
import android.app.PendingIntent
import android.content.Context
import android.content.Intent
import android.graphics.BitmapFactory
import android.graphics.Color
import android.media.RingtoneManager
import android.os.Build
import androidx.core.app.NotificationCompat
import androidx.core.app.NotificationManagerCompat
import com.blogspot.atifsoftwares.animatoolib.Animatoo
import java.net.URL

/*
 * We need to create a NotificationChannel associated with our CHANNEL_ID before sending a
 * notification.
 */
fun createChannel(context: Context) {
    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
        val notificationChannel = NotificationChannel(
            CHANNEL_ID,
            context.getString(R.string.channel_name),

            NotificationManager.IMPORTANCE_HIGH
        )
            .apply {
                setShowBadge(true)
            }

        notificationChannel.enableLights(true)
        notificationChannel.lightColor = Color.RED
        notificationChannel.enableVibration(true)
        notificationChannel.description = context.getString(R.string.notification_channel_description)

        val notificationManager = context.getSystemService(NotificationManager::class.java)
        notificationManager.createNotificationChannel(notificationChannel)
    }
}

/*
 * A Kotlin extension function for AndroidX's NotificationCompat that sends our Geofence
 * entered notification.  It sends a custom notification based on the name string associated
 * with the LANDMARK_DATA from GeofencingConstatns in the GeofenceUtils file.
 */

fun NotificationManager.sendGeofenceEnteredNotification(context: Context, foundIndex: Int, locate: String) {
    val mapImage = BitmapFactory.decodeResource(
        context.resources,
        R.drawable.map
    )

    // We use the name resource ID from the LANDMARK_DATA along with content_text to create
    // a custom message when a Geofence triggers.
    val builder = NotificationCompat.Builder(context, CHANNEL_ID)
        .setContentTitle("Current Location")
        .setContentText(locate)
        .setPriority(NotificationCompat.PRIORITY_HIGH)
        .setSmallIcon(R.drawable.kerangka)
        .setLargeIcon(mapImage)

    notify(NOTIFICATION_ID, builder.build())
}

fun sendNotificationRadius(context: Context, nama: String) {
    val intent = Intent(context, LogPosActivity::class.java).apply {
        putExtra("notifikasi","1")
        flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
    }

    val pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)

    val mapImage = BitmapFactory.decodeResource(
        context.resources,
        R.drawable.map
    )

    val builder = NotificationCompat.Builder(context, CHANNEL_ID)
        .setSmallIcon(R.drawable.kerangka)
        .setContentTitle("Peserta")
        .setContentText(nama+" berada diluar radius wilayah DISKOMINFO")
        .setLargeIcon(mapImage)
        .setContentIntent(pendingIntent)
        .setAutoCancel(true)
        .setPriority(NotificationCompat.PRIORITY_HIGH)
    with(NotificationManagerCompat.from(context)) {
        notify(NOTIFICATION_ID, builder.build())
    }
}

fun sendNotificationService(context: Context, nama: String, kode:String) {
    val intent = Intent(context, LogServiceActivity::class.java).apply {
        putExtra("notifikasi","1")
        putExtra("kode",kode)
        flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
    }

    val pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)

    val mapImage = BitmapFactory.decodeResource(
        context.resources,
        R.drawable.set
    )

    val builder = NotificationCompat.Builder(context, CHANNEL_ID)
        .setSmallIcon(R.drawable.kerangka)
        .setContentTitle("Peserta")
        .setContentText(nama+" mematikan service aplikasi/menutup aplikasi")
        .setLargeIcon(mapImage)
        .setContentIntent(pendingIntent)
        .setAutoCancel(true)
        .setPriority(NotificationCompat.PRIORITY_HIGH)
    with(NotificationManagerCompat.from(context)) {
        notify(NOTIFICATION_ID, builder.build())
    }
}

fun sendNotificationService(context: Context) {
    val builder = NotificationCompat.Builder(context, CHANNEL_ID)
        .setSmallIcon(R.drawable.kerangka)
        .setContentTitle("Admin")
        .setStyle(
            NotificationCompat.BigTextStyle()
                .bigText("Konfigurasi service telah dirubah, silahkan mulai ulang aplikasi SIMAPTA-PKL")
        )
        .setAutoCancel(true)
        .setPriority(NotificationCompat.PRIORITY_HIGH)
    with(NotificationManagerCompat.from(context)) {
        notify(NOTIFICATION_SERVICE, builder.build())
    }
}

fun sendNotificationPerbarui(context: Context, nama:String, kode:String) {
    val intent = Intent(context, PresensiActivity::class.java).apply {
        putExtra("notifikasi","1")
        putExtra("kode", kode)
        flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
    }

    val pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)

    val builder = NotificationCompat.Builder(context, CHANNEL_ID)
        .setSmallIcon(R.drawable.kerangka)
        .setContentTitle(nama)
        .setContentIntent(pendingIntent)
        .setAutoCancel(true)
        .setStyle(
            NotificationCompat.BigTextStyle()
                .bigText("Meminta izin kepada pembimbing untuk perbarui absensi pulang")
        )
        .setPriority(NotificationCompat.PRIORITY_HIGH)
    with(NotificationManagerCompat.from(context)) {
        notify(NOTIFICATION_PERBARUI, builder.build())
    }
}

fun sendNotificationBerhasilPerbarui(context: Context, nama:String) {
    val intent = Intent(context, HistoriActivity::class.java).apply {
        putExtra("notifikasi","1")
        flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
    }

    val pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)

    val builder = NotificationCompat.Builder(context, CHANNEL_ID)
        .setSmallIcon(R.drawable.kerangka)
        .setContentTitle(nama)
        .setContentText("Absensi pulang anda telah diperbarui")
        .setContentIntent(pendingIntent)
        .setAutoCancel(true)
        .setPriority(NotificationCompat.PRIORITY_HIGH)
    with(NotificationManagerCompat.from(context)) {
        notify(NOTIFICATION_PERBARUI, builder.build())
    }
}

fun sendNotificationPresensi(context: Context) {
    val intent = Intent(context, HuntMainActivity::class.java).apply {
        putExtra("notifikasi","1")
        flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
    }

    val pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)

    val builder = NotificationCompat.Builder(context, CHANNEL_ID)
        .setSmallIcon(R.drawable.kerangka)
        .setContentTitle("Pemberitahuan!!!")
        .setContentIntent(pendingIntent)
        .setAutoCancel(true)
        .setContentText("Hari ini anda belum melakukan absensi masuk")
        .setPriority(NotificationCompat.PRIORITY_HIGH)
    with(NotificationManagerCompat.from(context)) {
        notify(NOTIFICATION_PRESENSI, builder.build())
    }
}

fun sendNotificationIzin(context: Context, nama: String, surat: String, statusSurat: String, alasan: String) {
    val url = URL(surat);
    val image = BitmapFactory.decodeStream(url.openConnection().getInputStream());

    if(statusSurat.equals("approve")){
        val intent = Intent(context, HistoriActivity::class.java).apply {
            putExtra("notifikasi","1")
            flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
        }

        val pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)

        val builder = NotificationCompat.Builder(context, CHANNEL_ID)
            .setSmallIcon(R.drawable.kerangka)
            .setContentTitle("Surat Izin")
            .setStyle(
                NotificationCompat.BigPictureStyle().bigPicture(image).bigLargeIcon(null)
            )
            .setContentText(nama+" menyetujui izin ketidakhadiran")
            .setLargeIcon(image)
            .setContentIntent(pendingIntent)
            .setAutoCancel(true)
            .setPriority(NotificationCompat.PRIORITY_HIGH)

        with(NotificationManagerCompat.from(context)) {
            notify(NOTIFICATION_ID, builder.build())
        }
    }else{
        val intent = Intent(context, IzinActivity::class.java).apply {
            putExtra("notifikasi","1")
            flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
        }

        val pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)

        val builder = NotificationCompat.Builder(context, CHANNEL_ID)
            .setSmallIcon(R.drawable.kerangka)
            .setContentTitle("Surat Izin")
            .setStyle(
                NotificationCompat.BigTextStyle()
                    .bigText(nama+" tidak menyetujui surat izin ketidakhadiran dikarenakan : "+alasan)
            )
            .setContentText(nama+" tidak menyetujui izin ketidakhadiran")
            .setLargeIcon(image)
            .setContentIntent(pendingIntent)
            .setAutoCancel(true)
            .setPriority(NotificationCompat.PRIORITY_HIGH)

        with(NotificationManagerCompat.from(context)) {
            notify(NOTIFICATION_ID, builder.build())
        }
    }
}

fun sendNotificationIzinPeserta(context: Context, nama: String, surat:String) {
    val url = URL("http://192.168.43.57/simaptapkl/public/service/uploads/"+surat);
    val image = BitmapFactory.decodeStream(url.openConnection().getInputStream());

    val intent = Intent(context, SuratActivity::class.java).apply {
        putExtra("notifikasi","1")
        flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
    }

    val pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT)

    val builder = NotificationCompat.Builder(context, CHANNEL_ID)
        .setSmallIcon(R.drawable.kerangka)
        .setContentTitle("Surat Izin")
        .setStyle(
            NotificationCompat.BigPictureStyle().bigPicture(image).bigLargeIcon(null)
        )
        .setContentText(nama+" mengirim surat izin ketidakhadiran")
        .setLargeIcon(image)
        .setContentIntent(pendingIntent)
        .setAutoCancel(true)
        .setPriority(NotificationCompat.PRIORITY_HIGH)
    with(NotificationManagerCompat.from(context)) {
        notify(NOTIFICATION_ID, builder.build())
    }
}

private var NOTIFICATION_ID = 0
private var NOTIFICATION_PERBARUI = 5000
private var NOTIFICATION_PRESENSI = 1000
private var NOTIFICATION_SERVICE = 9000
private const val CHANNEL_ID = "GeofenceChannel"


