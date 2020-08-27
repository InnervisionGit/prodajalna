package com.example.inner.ep_trgovina;

import android.app.ListActivity;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;

import org.json.JSONArray;
import org.json.JSONObject;

import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

public class IzdelkiListActivity extends ListActivity {

    public static final String ALL_PRODUCTS = "http://188.230.175.68/netbeans/Seminarska/android/index.php?request=products";
    //doma preko rooterja ne dela. PC mora biti vklopljeno direktno v switch.
    //'10.0.2.2' za dostop preko emulatorja
    //'myIP' za dostop preko naprave
    public ArrayList<Izdelek> vsi_izdelki;
    public Izdelek javni;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        vsi_izdelki = new ArrayList<Izdelek>();
        new GetProductsAsync(getApplicationContext()).execute();
    }

    class GetProductsAsync extends AsyncTask<Void, Void, Izdelek[]> {
        private final Context context;
        public GetProductsAsync(Context c) {
            context = c;
        }

        @Override
        protected Izdelek[] doInBackground(Void... params) {
            try {
                HttpClient client = new DefaultHttpClient();
                HttpGet request = new HttpGet(ALL_PRODUCTS);
                HttpResponse response = client.execute(request);
                HttpEntity httpEntity = response.getEntity();
                //for them med debug skilz
                //String error = EntityUtils.toString(httpEntity);
                //Log.d("napaka", error);
                JSONObject json = new JSONObject(EntityUtils.toString(httpEntity));

                if (json.getString("status").equals("success")) {
                    JSONArray items = json.getJSONArray("payload");
                    Izdelek[] izdelki = new Izdelek[items.length()];

                    for (int i = 0; i < items.length(); i++) {
                        JSONObject o = items.getJSONObject(i);

                        int id = o.getInt("Item_ID");
                        String ime = o.getString("Item_name");
                        double cena =o.getDouble("Item_price");
                        String opis = o.getString("Item_description");
                        String url = o.getString("Item_URL");
                        izdelki[i] = new Izdelek(id, ime, cena, opis, url);
                        javni = new Izdelek(id, ime, cena, opis, url);
                        vsi_izdelki.add(javni);
                    }

                    return izdelki;
                }

            } catch (Exception e) {
                e.printStackTrace();
            }

            return null;
        }

        @Override
        protected void onPostExecute(Izdelek[] izdelki) {
            if (izdelki == null) {
                Toast.makeText(context, "Napaka", Toast.LENGTH_LONG).show();
            } else {
                ArrayAdapter<Izdelek> adapter = new ArrayAdapter<Izdelek>(IzdelkiListActivity.this, android.R.layout.simple_list_item_1, izdelki);
                setListAdapter(adapter);
            }
        }
    }
    @Override

    protected void onListItemClick(ListView l, View v, int position, long id) {
        super.onListItemClick(l, v, position, id);
        //poklicemo drugi ekran z vsemi podatki o produktih
        Izdelek poklicanIzdelek = vsi_izdelki.get(position);
        Intent intent = new Intent(IzdelkiListActivity.this, PrikazIzdelkaActivity.class);
        intent.putExtra("url", poklicanIzdelek.getUrl());
        intent.putExtra("ime", poklicanIzdelek.getIme());
        intent.putExtra("opis", poklicanIzdelek.getOpis());
        intent.putExtra("cena", poklicanIzdelek.getCena());
        startActivity(intent);
        finish();

    }

}
