/**
 Author(s): Ian M. Riley
            Courtney E. Cole
 Project Name: Fridge Friend
 Project Description: An expiration date tracker with built-in shopping list and memo features.
 Date Last Edited: 4-17-2018
 */

// Import packages from the library that are necessary.
package quantumcake.com.fridgefriend;
import android.content.ContentValues;
import android.content.Context;
import android.content.res.Resources;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.support.v4.content.ContextCompat;
import android.util.Log;

import java.util.ArrayList;
import java.util.GregorianCalendar;

// It's a helper. Where we store the data, even after you close the application.
public class SQLiteHelper extends SQLiteOpenHelper
{
    // Declare the table and the columns.
    private static final String DB_NAME = "ItemDB";
    private static final String TABLE_NAME = "Food";
    public static final String COLUMN_NAME = "Name";
    public static final String COLUMN_DATE = "Date";
    public static final String COLUMN_STORAGE = "Storage";
    private static final int DB_VERSION = 3;
    private Context mContext;
    
    // Constructor does constructing.
    public SQLiteHelper(Context context)
    {
        super(context, DB_NAME, null, DB_VERSION);
        mContext = context;
    }
    
    // Make a query with the table.
    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase)
    {
        // Does some magic, that we kind of still don't understand.
        String query = "CREATE TABLE " + TABLE_NAME + "("
                + COLUMN_NAME + " NAME," + COLUMN_DATE + " DATE,"
                + COLUMN_STORAGE + " STORAGE" + ")";
        sqLiteDatabase.execSQL(query);
    }
    
    // Inserts a new item. Exciting, right?
    public void insertNewItem(String name, long date, String storage)
    {
        // Make the database.
        SQLiteDatabase db = this.getWritableDatabase();
        
        // Make the values.
        ContentValues values = new ContentValues();
        values.put(COLUMN_NAME, name);
        values.put(COLUMN_DATE, date);
        values.put(COLUMN_STORAGE, storage);
        
        // Put them in there.
        db.insert(TABLE_NAME, null, values);
        db.close();
    }
    
    // Delete it. What else could this possibly be?
    public void deleteItem(String item)
    {
        // Make the database. Delete it.
        SQLiteDatabase db = this.getWritableDatabase();
        db.delete(TABLE_NAME, COLUMN_NAME + " = ?", new String[]{item});
        db.close();
    }
    
    // Deletes the expired things. It's the name.
    public void deleteExpiredItems(String storage, boolean addToList)
    {
        // Do something with the cursor that involves bashing one's head against the keyboard in order to properly program.
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor c = db.query(TABLE_NAME, new String[]{COLUMN_NAME, COLUMN_DATE, COLUMN_STORAGE}, null, null, null, null, COLUMN_DATE);
        c.moveToFirst();
        
        // Declare stuff.
        String name;
        long time;
        
        // The cursor goes through each of the tings in the storage to check if it is expired.
        while (!c.isAfterLast())
        {
            // Move to the next thing if it is not in the storage we want.
            if (!c.getString(c.getColumnIndex(COLUMN_STORAGE)).equals(storage))
            {
                c.moveToNext();
                continue;
            }
            
            // Get the date.
            time = c.getLong(c.getColumnIndex(COLUMN_DATE));
            
            // If the date of the item is later, then it hasn't expired yet. So... ABORT!
            long current = new GregorianCalendar().getTimeInMillis();
            current = current - (current % (1000 * 60 * 60 * 24));
            
            if (time > current)
            {
                c.moveToNext();
                continue;
            }
            
            // If it is expired, we get the name.
            name = c.getString(0);
            
            // Delete it.
            deleteItem(name);
            
            // If you said to add it to the list, add it.
            if (addToList)
                insertNewItem(name, Long.MAX_VALUE,  "LIST:");
            
            // Move on.
            c.moveToNext();
        }
        
        // Close it. We are done here.
        db.close();
    }
    
    // Get names. Throws an array list at your face.
    public ArrayList<String> getNames(String storage)
    {
        ArrayList<String> list = new ArrayList<>();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor c = db.query(TABLE_NAME, new String[]{COLUMN_NAME, COLUMN_DATE, COLUMN_STORAGE}, null, null, null, null, storage.contains("LIST") ? COLUMN_NAME + " COLLATE NOCASE" : COLUMN_DATE);
        while(c.moveToNext())
        {
            if (c.getString(c.getColumnIndex(COLUMN_STORAGE)).equals(storage))
                list.add(c.getString(c.getColumnIndex(COLUMN_NAME)));
        }
        c.close();
        db.close();
        return list;
    }
    
    // Gets dates. Throws an array list at your face.
    public ArrayList<Long> getDates(String storage)
    {
        ArrayList<Long> list = new ArrayList<>();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor c = db.query(TABLE_NAME, new String[]{COLUMN_NAME, COLUMN_DATE, COLUMN_STORAGE}, null, null, null, null, storage.contains("LIST") ? COLUMN_NAME + " COLLATE NOCASE" : COLUMN_DATE);
        while(c.moveToNext())
        {
            if (c.getString(c.getColumnIndex(COLUMN_STORAGE)).equals(storage))
                list.add(c.getLong(c.getColumnIndex(COLUMN_DATE)));
        }
        c.close();
        db.close();
        return list;
    }
    
    // Gets storages. Throws an array list at your face.
    public ArrayList<String> getStorages(String storage)
    {
        ArrayList<String> list = new ArrayList<>();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor c = db.query(TABLE_NAME, new String[]{COLUMN_NAME, COLUMN_DATE, COLUMN_STORAGE}, null, null, null, null, storage.contains("LIST") ? COLUMN_NAME + " COLLATE NOCASE" : COLUMN_DATE);
        while(c.moveToNext())
        {
            if (c.getString(c.getColumnIndex(COLUMN_STORAGE)).equals(storage))
                list.add(c.getString(c.getColumnIndex(COLUMN_STORAGE)));
        }
        c.close();
        db.close();
        return list;
    }
    
    // This gets all of them. We never called it. Oh well, there's part of our lives that we will never get back.
    public ArrayList<String> getAll()
    {
        ArrayList<String> list = new ArrayList<>();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor c = db.query(TABLE_NAME, new String[]{COLUMN_NAME, COLUMN_DATE, COLUMN_STORAGE}, null, null, null, null, COLUMN_DATE);
        while(c.moveToNext())
        {
            list.add(c.getString(c.getColumnIndex(COLUMN_NAME)));
            list.add(c.getString(c.getColumnIndex(COLUMN_DATE)));
            list.add(c.getString(c.getColumnIndex(COLUMN_STORAGE)));
        }
        c.close();
        db.close();
        return list;
    }
    
    // Checks if there is an item with the same name.
    public boolean hasItemWithName(String name)
    {
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor c = db.query(TABLE_NAME, new String[]{COLUMN_NAME, COLUMN_DATE, COLUMN_STORAGE}, null, null, null, null, null);
        while(c.moveToNext())
        {
            if (c.getString(c.getColumnIndex(COLUMN_NAME)).equals(name))
                return false;
        }
        return true;
    }
    
    // It's required. Don't look in it. There's nothing there.
    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int i, int i1)
    {
        // Why did you look here? There's nothing here.
    }
}
