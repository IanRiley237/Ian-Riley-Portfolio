/**
 Author(s): Ian M. Riley
            Courtney E. Cole
 Project Name: Fridge Friend
 Project Description: An expiration date tracker with built-in shopping list and memo features.
 Date Last Edited: 4-17-2018
 */

// Import packages from the library that are necessary.
package quantumcake.com.fridgefriend;
import android.content.Context;
import android.support.v4.content.ContextCompat;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import java.util.ArrayList;
import java.util.GregorianCalendar;

// It's an adapter. A custom one we made for our lists. It's basically three things.
public class CustomAdapter extends RecyclerView.Adapter<CustomAdapter.MyViewHolder>
{
    // The three things of the adapter.
    private ArrayList<String> names;
    private ArrayList<Long> dates;
    private ArrayList<String> storages;
    private Context mContext;
    private boolean isList;
    
    // WHY IS THIS CONSTRUCTOR FILLED WITH THE GREATEST INCONSISTENCY?!
    public CustomAdapter(Context context, ArrayList<String> mNames, ArrayList<Long> mDates, ArrayList<String> mStorages, boolean mIsList) {
        names = mNames;
        dates = mDates;
        storages = mStorages;
        isList = mIsList;
        mContext = context;
    }
    
    // It filled in these methods for me.
    @Override
    public MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType)
    {
        View view = LayoutInflater.from(mContext).inflate( isList ? R.layout.item_for_shopping : R.layout.item, parent, false);
        MyViewHolder holder = new MyViewHolder(view);
        return holder;
    }
    
    // Creates and implements a defined color-code.
    @Override
    public void onBindViewHolder(final MyViewHolder holder, final int position) {
        holder.nameText.setText(names.get(position));
        if (!isList)
        {
            // What is today? Oh, here it is, thanks to this handy line of code!
            
            // This makes it the colors.
            long current = new GregorianCalendar().getTimeInMillis();
            current = current - (current % (1000 * 60 * 60 * 24));
            long diff = (dates.get(position) - current) / (1000 * 60 * 60 * 24);
            holder.dateText.setText(dates.get(position) != Long.MAX_VALUE ? (diff > 0 ? String.valueOf(diff) + " day" + (diff == 1 ? "" : "s") : "EXPIRED") : "Non-Perishable");
            holder.dateText.setTextColor(ContextCompat.getColor(mContext,
                    (diff <= 0 ? R.color.notime :
                            diff <= 3 ? R.color.littletime :
                                    diff <= 7 ? R.color.sometime :
                                            diff <= 14 ? R.color.enoughtime :
                                                    dates.get(position) < Long.MAX_VALUE ? R.color.Black :
                                                            R.color.infinitetime
                    )
            ));
        }
    }
    
    // Gets the item count. Never would have guessed.
    @Override
    public int getItemCount() {
        return names.size();
    }
   
    // Sets the textViews appropriately.
    class MyViewHolder extends RecyclerView.ViewHolder
    {
        // Makes the textViews.
        public TextView nameText;
        public TextView dateText;
    
        // Sets the textViews equal to the days remaining.
        public MyViewHolder(View itemView)
        {
            super(itemView);
            nameText = (TextView)itemView.findViewById(R.id.item_name);
            dateText = (TextView)itemView.findViewById(R.id.expiration_time);
        }
    }
}