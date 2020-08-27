package com.example.inner.ep_trgovina;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.Drawable;
import android.os.StrictMode;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

public class PrikazIzdelkaActivity extends AppCompatActivity {
    public String url,ime,opis,cena;
    //public String slike = "http://10.0.2.2/netbeans/seminar/slike/";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_prikaz_izdelka);
        //ce ne dodamo tega potem nemoremo delat network operacij na main threadu
        //nepravilen fix vendar dela
        StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);

        Intent intent = getIntent();
        url = intent.getStringExtra("url");
        ime = intent.getStringExtra("ime");
        opis = intent.getStringExtra("opis");
        cena = intent.getStringExtra("cena");

        TextView ime_cena = (TextView) findViewById(R.id.imeincena);
        ime_cena.setText(ime + " : "+ cena);
        ImageView slika = (ImageView) findViewById(R.id.imageView);
        //nastimi sliko
        if (url == null){
            slika.setImageResource(R.drawable.nophoto);
        }else{
            //String[] split = url.split("/");
            //String slikca = split[split.length-1];
            //slike=slike+""+slikca;
            Bitmap bitmap;
            try {
                URL imageURL = new URL(url);
                HttpURLConnection connection= (HttpURLConnection)imageURL.openConnection();
                connection.setDoInput(true);
                connection.connect();
                InputStream inputStream = connection.getInputStream();
                bitmap = BitmapFactory.decodeStream(inputStream);
                slika.setImageBitmap(bitmap);

            } catch (Exception e) {
                e.printStackTrace();
            }

        }

        TextView opisIzdelka = (TextView) findViewById(R.id.opis);
        opisIzdelka.setText(opis);
    }

    public void mainActivity(View view) {
        Intent intent = new Intent(PrikazIzdelkaActivity.this, MainActivity.class);
        startActivity(intent);
        finish();
    }
}
