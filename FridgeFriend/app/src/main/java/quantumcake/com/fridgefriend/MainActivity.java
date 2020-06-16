/**
 Author(s): Ian M. Riley
            Courtney E. Cole
 Project Name: Fridge Friend
 Project Description: An expiration date tracker with built-in shopping list and memo features.
 Date Last Edited: 4-17-2018
 */

// Import packages from the library that are necessary.
package quantumcake.com.fridgefriend;
import android.app.DatePickerDialog;
import android.content.DialogInterface;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.DividerItemDecoration;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.TypedValue;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.GregorianCalendar;

// The MainActivity. Woah.
public class MainActivity extends AppCompatActivity
{
    // Declare necessary global variables.
    SQLiteHelper dbHelper;
    RecyclerView.Adapter adapter;
    private RecyclerView.LayoutManager layoutManager;
    RecyclerView recyclerView;
    private static ArrayList<String> names;
    private static ArrayList<Long> dates;
    private static ArrayList<String> storages;
    private DatePickerDialog.OnDateSetListener mDateListener;
    private Button removeBtn;
    
    // Performs the included actions when the Application is created.
    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        // Generic Android Studio Biz.
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        
        // Assign variables.
        recyclerView = (RecyclerView) findViewById(R.id.recycler_view); // Identify the RecyclerView.
        removeBtn = (Button) findViewById(R.id.removebtn);
        layoutManager = new LinearLayoutManager(this); // Set up the LayoutManager.
        recyclerView.setLayoutManager(layoutManager);
        dbHelper = new SQLiteHelper(this);
        
        // The lists of names, dates, and storage locations.
        names = new ArrayList<>();
        dates = new ArrayList<>();
        storages = new ArrayList<>();
        
        // Sets the fridge to display by default.
        displayFridge(findViewById(R.id.listTitle));
        
        // Add dividers in between each item.
        DividerItemDecoration dividerItemDecoration = new DividerItemDecoration(recyclerView.getContext(), LinearLayoutManager.VERTICAL);
        recyclerView.addItemDecoration(dividerItemDecoration);
        
        // Makes it so that the buttons on the application are blue.
        Button add = findViewById(R.id.addbtn), remove = findViewById(R.id.removebtn);
        add.setBackgroundColor(getResources().getColor(R.color.colorPrimaryDark));
        remove.setBackgroundColor(getResources().getColor(R.color.colorPrimaryDark));
        
        // Updates the RecyclerView to show items.
        showItemList();
    }
    
    // Adds an item and follows a specific procedure based off of what section of lists that we are in. Also, the messiest method.
    public void addItem(View view)
    {
        // Declaring constants for the "Add Item" options box. Need to be final, or the AlertDialog gets upset.
        final LinearLayout layout = new LinearLayout(this);
        final Calendar cal = Calendar.getInstance();
        final GregorianCalendar date = new GregorianCalendar();
        final Button dateButton = new Button(this);
        final CheckBox check = new CheckBox(this);
        final EditText itemName = new EditText(this);
        final String listTitle = (String) ((TextView) findViewById(R.id.listTitle)).getText();
        
        // Makes the "Add Item" options box write vertically.
        layout.setOrientation(LinearLayout.VERTICAL);
        
        // Sets the hint for the TextField and adds it to the top.
        itemName.setHint("Name");
        layout.addView(itemName);
        
        // Changes the text of the button.
        dateButton.setText("Select a Date");
        
        // The biggest if statement in the world. Checks if we are in a storage location.
        if (!listTitle.equals(getString(R.string.list)) && !listTitle.equals(getString(R.string.memo)))
        {
            // Creates the option to check if the item is a non-perishable.
            check.setText("Is the item a non-perishable?");
            
            // If the check box is chosen, get rid of the date button.
            check.setOnClickListener(new View.OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    // TODO Auto-generated method stub
                    if(check.isChecked())
                    {
                        layout.removeView(dateButton);
                    }
                    else
                    {
                        layout.addView(dateButton);
                    }
                }
            });
            
            // Makes the button pull up the calender and acquires the day, month, and year from the user.
            dateButton.setOnClickListener(new View.OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    // Finds the current day, month, and year
                    int day = cal.get(Calendar.DAY_OF_MONTH);
                    int month = cal.get(Calendar.MONTH);
                    int year = cal.get(Calendar.YEAR);
                    
                    // Sets the current day, month, and year found above to be the default selection. Also, creates it.
                    DatePickerDialog datePickerDialog = new DatePickerDialog(
                                                                             MainActivity.this,
                                                                              android.R.style.Theme_Holo_Light_Dialog_MinWidth,
                                                                              mDateListener,
                                                                              year,month,day);
                    datePickerDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
                    datePickerDialog.show();
                }
            });
            
            // Upon hitting okay, stores the date inputted by the user.
            mDateListener = new DatePickerDialog.OnDateSetListener()
            {
                @Override
                public void onDateSet(DatePicker datePicker, int year, int month, int day)
                {
                    dateButton.setText(month + 1 + "/" + day + "/" + year);
                    date.set(year, month, day);
                }
            };
            
            // Adds the checkbox and button if we are adding an item to a storage location.
            layout.addView(check);
            layout.addView(dateButton);
        }
        
        // Creates the dialog box for adding the item in full.
        final AlertDialog dialog = new AlertDialog.Builder(this)
                .setTitle("Add Item")
                .setMessage(!listTitle.equals(getString(R.string.memo)) ? ("What is the name" + (!listTitle.equals(getString(R.string.list)) ? " and expiration date" : "") + " of the item?") : ("What is your memo?"))
                .setView(layout)
                .setPositiveButton("Add", new DialogInterface.OnClickListener()
                {
                    @Override
                    public void onClick(DialogInterface dialog, int which)
                    {
                        // We set the value of the OnClick that should be here elsewhere. This makes only dialog.dismiss able to close the window.
                    }
                })
                .setNeutralButton("Cancel", null)
                .create();
        dialog.show();
        
        // Adds the item that they user inputted only if all of the fields have been added to.
        dialog.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener(new View.OnClickListener()
        {
            @Override
            public void onClick(View view)
            {
                // Takes in the name of the item and ensures that it is unique to itself, and not the name of another item that has already been created.
                String name = String.valueOf(itemName.getText());
                boolean unique = dbHelper.hasItemWithName(name), picked = String.valueOf(dateButton.getText()).contains("/") || check.isChecked();
                
                // Takes the name of the storage to know which storage the item should be placed in.
                TextView tv = (TextView) findViewById(R.id.listTitle);
                
                // Makes sure that the storage area selected is valid.
                boolean isStorage = !String.valueOf(tv.getText()).equals(getString(R.string.list)) && !String.valueOf(tv.getText()).equals(getString(R.string.memo));
                
                // Adds item or tells the user why the item cannot be added at this time.
                if (!name.equals("") && ((unique && picked) || !isStorage))
                {
                    String storage = (String) tv.getText();
                    dbHelper.insertNewItem(name, check.isChecked() ? Long.MAX_VALUE : (date.getTimeInMillis() - (date.getTimeInMillis() % (1000 * 60 * 60 * 24))), storage);
                    Toast.makeText(MainActivity.this, name + " has been added to the list", Toast.LENGTH_LONG).show();
                    showItemList();
                    dialog.dismiss();
                }
                else
                {
                    if (name.equals(""))
                        Toast.makeText(MainActivity.this, "Please type an item name", Toast.LENGTH_LONG).show();
                    else if (!unique)
                        Toast.makeText(MainActivity.this, "Please type in a unique item name", Toast.LENGTH_LONG).show();
                    else if (!picked)
                        Toast.makeText(MainActivity.this, "Please choose an expiration date or mark as non-perishable", Toast.LENGTH_LONG).show();
                    else
                        Toast.makeText(MainActivity.this, "Something went wrong", Toast.LENGTH_LONG).show();
                }
            }
        });
        
    }
    
    // Removes the item selected
    public void removeItem(View view)
    {
        // A bunch of variable declaration. Sets the orientation to vertical again as well.
        View parent = (View) view.getParent().getParent();
        TextView itemTextView = (TextView) parent.findViewById(R.id.item_name);
        if (itemTextView == null) return;
        final String item = String.valueOf(itemTextView.getText());
        final LinearLayout layout = new LinearLayout(this);
        layout.setOrientation(LinearLayout.VERTICAL);
        String listTitle = (String) ((TextView) findViewById(R.id.listTitle)).getText();
        
        // Checks if the we are in the Shopping List
        if (listTitle.equals(getString(R.string.list)))
        {
            // Had to display the radio buttons as two separate lists, but make them work as one. Get ready for some round about bullshit.
            LinearLayout radioButtonLayout = new LinearLayout(this);
            radioButtonLayout.setOrientation(LinearLayout.VERTICAL);
            
            // Declare each row of radio buttons. The otherGroup is unused, but deleting it makes Android Studio salty.
            final RadioGroup foodGroup = new RadioGroup(this);
            final RadioGroup nonFoodGroup = new RadioGroup(this);
            final RadioGroup otherGroup = new RadioGroup(this);
            
            // Declares the buttons and sets their text appropriately.
            final RadioButton freezer = new RadioButton(this),
                              fridge = new RadioButton(this),
                              pantry = new RadioButton(this),
                              beauty = new RadioButton(this),
                              other = new RadioButton(this);
            freezer.setText("Freezer");
            fridge.setText("Fridge");
            pantry.setText("Pantry");
            beauty.setText("Beauty");
            other.setText("Other");
            
            // Sets the orientation of each group to horizontal.
            foodGroup.setOrientation(RadioGroup.HORIZONTAL);
            nonFoodGroup.setOrientation(RadioGroup.HORIZONTAL);
            otherGroup.setOrientation(RadioGroup.HORIZONTAL);
            
            // Add the buttons to the appropriate groups.
            foodGroup.addView(freezer);
            foodGroup.addView(fridge);
            foodGroup.addView(pantry);
            nonFoodGroup.addView(beauty);
            nonFoodGroup.addView(other);
            
            // Add the groups to the layout.
            radioButtonLayout.addView(foodGroup);
            radioButtonLayout.addView(nonFoodGroup);
            radioButtonLayout.addView(otherGroup);
            
            // This is the dumb part. There's like five of these. Makes sure none of the buttons are pressed after we select the one we want.
            freezer.setOnClickListener(new View.OnClickListener()
            {
                public void onClick(View v)
                {
                    freezer.setChecked(true);
                    nonFoodGroup.clearCheck();
                    otherGroup.clearCheck();
                }
            });
            fridge.setOnClickListener(new View.OnClickListener()
            {
                public void onClick(View v)
                {
                    fridge.setChecked(true);
                    nonFoodGroup.clearCheck();
                    otherGroup.clearCheck();
                }
            });
            pantry.setOnClickListener(new View.OnClickListener()
            {
                public void onClick(View v)
                {
                    pantry.setChecked(true);
                    nonFoodGroup.clearCheck();
                    otherGroup.clearCheck();
                }
            });
            beauty.setOnClickListener(new View.OnClickListener()
            {
                public void onClick(View v)
                {
                    beauty.setChecked(true);
                    foodGroup.clearCheck();
                    otherGroup.clearCheck();
                }
            });
            other.setOnClickListener(new View.OnClickListener()
            {
                public void onClick(View v)
                {
                    other.setChecked(true);
                    foodGroup.clearCheck();
                    otherGroup.clearCheck();
                }
            });
            
            // I heard you like layouts, so I added a layout to the layout so you can layout while you layout. Dawg.
            layout.addView(radioButtonLayout);
            
            // Starts to look like the add button again, but not organized the same at all. For some reason. Apparently...
            final Button dateButton = new Button(this);
            dateButton.setText("Select a Date");
            final Calendar cal = Calendar.getInstance();
            final GregorianCalendar date = new GregorianCalendar();
            final CheckBox check = new CheckBox(this);
            check.setText("Is the item a non-perishable?");
            
            // Does the same date thing, but no brackets this time.
            check.setOnClickListener(new View.OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    // TODO Auto-generated method stub
                    if(check.isChecked())
                        layout.removeView(dateButton);
                    else
                        layout.addView(dateButton);
                }
            });
            
            // More of the same thing. We copied and pasted it instead of making a function because we just don't care at this point.
            dateButton.setOnClickListener(new View.OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    int day = cal.get(Calendar.DAY_OF_MONTH);
                    int month = cal.get(Calendar.MONTH);
                    int year = cal.get(Calendar.YEAR);
                    DatePickerDialog datePickerDialog = new DatePickerDialog(
                                                                             MainActivity.this,
                                                                             android.R.style.Theme_Holo_Light_Dialog_MinWidth,
                                                                             mDateListener,
                                                                             year,month,day);
                    datePickerDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
                    datePickerDialog.show();
                }
            });
            
            // Make the date picker do the thing. We did it up above. Reference those comments. I'm tired.
            mDateListener = new DatePickerDialog.OnDateSetListener()
            {
                @Override
                public void onDateSet(DatePicker datePicker, int year, int month, int day)
                {
                    dateButton.setText(month + 1 + "/" + day + "/" + year);
                    date.set(year, month, day);
                }
            };
            
            // Add the check and the date button again, because this method sucks.
            layout.addView(check);
            layout.addView(dateButton);
            
            // We do the whole song and dance with the alert dialog again, but with slightly different text.
            final AlertDialog dialog = new AlertDialog.Builder(this)
                    .setTitle("You are about to delete this item.")
                    .setMessage("Would you like to add " + item + " to the tracker?")
                    .setView(layout)
                    .setPositiveButton("Add", new DialogInterface.OnClickListener()
                    {
                        @Override
                        public void onClick(DialogInterface dialog, int which)
                        {
                            // Same dislocation again. Whee.
                        }
                    })
                    
                    // Delete removes all that we just did with absolute no remorse.
                    .setNegativeButton("Delete", new DialogInterface.OnClickListener()
                    {
                        @Override
                        public void onClick(DialogInterface dialogInterface, int i)
                        {
                            dbHelper.deleteItem(item);
                            Toast.makeText(MainActivity.this, item + " deleted", Toast.LENGTH_LONG).show();
                            showItemList();
                        }
                    })
                    .setNeutralButton("Cancel", null)
                    .create();
            dialog.show();
            
            // This is the button that does the magic. It's the "move from list to other list" button.
            dialog.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener(new View.OnClickListener()
            {
                @Override
                public void onClick(View view)
                {
                    // Puts the item in the correct storage place. We use an unsafe and frankly idiotic way to check if a date was added. But hey, it works.
                    String storage;
                    boolean picked = (String.valueOf(dateButton.getText()).contains("/") || check.isChecked());
                    if (freezer.isChecked())
                        storage = getString(R.string.freezer);
                    else if (fridge.isChecked())
                        storage = getString(R.string.fridge);
                    else if (pantry.isChecked())
                        storage = getString(R.string.pantry);
                    else if (beauty.isChecked())
                        storage = getString(R.string.beauty);
                    else if (other.isChecked())
                        storage = getString(R.string.other);
                    else
                        storage = null;
                    
                    // If they picked a storage place and a date, then move it.
                    if (storage != null && picked)
                    {
                        // In Mother Russia, we delete THEN insert the item.
                        dbHelper.deleteItem(item);
                        dbHelper.insertNewItem(item, check.isChecked() ? Long.MAX_VALUE : date.getTimeInMillis(), storage);
                        Toast.makeText(MainActivity.this, item + " moved to " + storage.substring(0, storage.length() - 1).toLowerCase(), Toast.LENGTH_LONG).show();
                        showItemList();
                        dialog.dismiss();
                    }
                    else
                    {
                        // Yay. You messed up, boi. Here's an error. Try-Catches are overrated.
                        if (storage == null)
                            Toast.makeText(MainActivity.this, "Please select a storage location", Toast.LENGTH_LONG).show();
                        else if (!picked)
                            Toast.makeText(MainActivity.this, "Please choose an expiration date or mark as non-perishable", Toast.LENGTH_LONG).show();
                        else
                            Toast.makeText(MainActivity.this, "Something went wrong", Toast.LENGTH_LONG).show();
                    }
                }
            });
        }
        else if(listTitle.equals(getString(R.string.memo)))
        {
            // Delete the memo. So much easier.
            AlertDialog dialog = new AlertDialog.Builder(this)
                .setTitle("You are about to delete this memo.")
                .setMessage("Are you sure you want to delete this memo?")
                .setView(layout)
                .setPositiveButton("Delete", new DialogInterface.OnClickListener()
                {
                    @Override
                    public void onClick(DialogInterface dialog, int which)
                    {
                        dbHelper.deleteItem(item);
                        Toast.makeText(MainActivity.this, "Memo deleted.", Toast.LENGTH_LONG).show();
                        showItemList();
                    }
                })
                .setNeutralButton("Cancel", null)
                .create();
            dialog.show();
        }
        else
        {
            // Before deleting the item in storage, ask if they want to add it to the shopping list.
            AlertDialog dialog = new AlertDialog.Builder(this)
                    .setTitle("You are about to delete this item.")
                    .setMessage("Would you like to move " + item + " to the shopping list?")
                    .setView(layout)
                    .setPositiveButton("Add", new DialogInterface.OnClickListener()
                    {
                        // Move the item to the shopping list.
                        @Override
                        public void onClick(DialogInterface dialog, int which)
                        {
                            dbHelper.deleteItem(item);
                            dbHelper.insertNewItem(item, Long.MAX_VALUE, getString(R.string.list));
                            Toast.makeText(MainActivity.this, item + " moved to shopping list", Toast.LENGTH_LONG).show();
                            showItemList();
                        }
                    })
                    .setNegativeButton("Delete", new DialogInterface.OnClickListener()
                    {
                        // Or don't. That's your choice.
                        @Override
                        public void onClick(DialogInterface dialogInterface, int i)
                        {
                            dbHelper.deleteItem(item);
                            Toast.makeText(MainActivity.this, item + " deleted", Toast.LENGTH_LONG).show();
                            showItemList();
                        }
                    })
                    .setNeutralButton("Cancel", null)
                    .create();
            dialog.show();
        }
    }
    
    // Removes all the expired item in just the one storage that we are in.
    public void removeExpired(View view)
    {
        // Does the ".getParent().getParent()" thing again.
        View parent = (View) view.getParent().getParent();
        TextView itemTextView = (TextView) parent.findViewById(R.id.item_name);
        if (itemTextView == null) return;
        final String item = String.valueOf(itemTextView.getText());
        
        // More vertical action. Boom. Pow. Ka-chow.
        final LinearLayout layout = new LinearLayout(this);
        layout.setOrientation(LinearLayout.VERTICAL);
        
        // The title of the list. Get the text from that. Yep.
        TextView listTitleView = findViewById(R.id.listTitle);
        final String storage = (String) listTitleView.getText();
        
        // Get ready to build another AlertDialog.
        AlertDialog dialog = new AlertDialog.Builder(this)
                .setTitle("You are about to delete all expired items.")
                .setMessage("Would you like to move all expired items from this list to your shopping list?")
                .setView(layout)
                .setPositiveButton("Add", new DialogInterface.OnClickListener()
                {
                    // Add stuff.
                    @Override
                    public void onClick(DialogInterface dialog, int which)
                    {
                        dbHelper.deleteExpiredItems(storage, true);
                        showItemList();
                    }
                })
                .setNegativeButton("Delete", new DialogInterface.OnClickListener()
                {
                    // Don't.
                    @Override
                    public void onClick(DialogInterface dialog, int which)
                    {
                        dbHelper.deleteExpiredItems(storage, false);
                        showItemList();
                    }
                })
                .setNeutralButton("Cancel", null)
                .create();
        dialog.show();
        
    }
    
    // Gets called every single time you add or remove something. Just updates the list.
    private void showItemList()
    {
        // Gets the title, gets the location name. Sets the text size.
        TextView listTitleView = findViewById(R.id.listTitle);
        String storage = (String) listTitleView.getText();
        listTitleView.setTextSize(TypedValue.COMPLEX_UNIT_SP, 16f);
        
        // Makes a new adapter. Add it to the recyclerView.
        adapter = new CustomAdapter(this, names, dates, storages, storage.equals(getString(R.string.list)) || storage.equals((getString((R.string.memo)))));
        recyclerView.setAdapter(adapter);
        
        // If the adapter has something in it, clear it and replace.
        if(adapter != null)
        {
            names.clear();
            dates.clear();
            storages.clear();
            names.addAll(dbHelper.getNames(storage));
            dates.addAll(dbHelper.getDates(storage));
            storages.addAll(dbHelper.getStorages(storage));
            adapter.notifyDataSetChanged();
        }
    }
    
    // Displays the freezer.
    public void displayFreezer(View view)
    {
        LinearLayout ll = findViewById(R.id.buttonHolder);
        if (ll.findViewById(R.id.removebtn) == null)
            ll.addView(removeBtn);
        TextView tv = (TextView) findViewById(R.id.listTitle);
        tv.setText(R.string.freezer);
        showItemList();
    }
    
    // Displays the fridge.
    public void displayFridge(View view)
    {
        LinearLayout ll = findViewById(R.id.buttonHolder);
        if (ll.findViewById(R.id.removebtn) == null)
            ll.addView(removeBtn);
        TextView tv = (TextView) findViewById(R.id.listTitle);
        tv.setText(R.string.fridge);
        showItemList();
    }
    
    // Displays the pantry.
    public void displayPantry(View view)
    {
        LinearLayout ll = findViewById(R.id.buttonHolder);
        if (ll.findViewById(R.id.removebtn) == null)
            ll.addView(removeBtn);
        TextView tv = (TextView) findViewById(R.id.listTitle);
        tv.setText(R.string.pantry);
        showItemList();
    }
    
    // Displays the beauty.
    public void displayBeauty(View view)
    {
        LinearLayout ll = findViewById(R.id.buttonHolder);
        if (ll.findViewById(R.id.removebtn) == null)
            ll.addView(removeBtn);
        TextView tv = (TextView) findViewById(R.id.listTitle);
        tv.setText(R.string.beauty);
        showItemList();
    }
    
    // Displays the other.
    public void displayOther(View view)
    {
        LinearLayout ll = findViewById(R.id.buttonHolder);
        if (ll.findViewById(R.id.removebtn) == null)
            ll.addView(removeBtn);
        TextView tv = (TextView) findViewById(R.id.listTitle);
        tv.setText(R.string.other);
        showItemList();
    }
    
    // Displays the memo.
    public void displayMemo(View view)
    {
        LinearLayout ll = findViewById(R.id.buttonHolder);
        if (ll.findViewById(R.id.removebtn) != null)
            ll.removeView(removeBtn);
        TextView tv = (TextView) findViewById(R.id.listTitle);
        tv.setText(R.string.memo);
        showItemList();
    }
    
    // Displays the list.
    public void displayShoppingList(View view)
    {
        LinearLayout ll = findViewById(R.id.buttonHolder);
        if (ll.findViewById(R.id.removebtn) != null)
            ll.removeView(removeBtn);
        TextView tv = (TextView) findViewById(R.id.listTitle);
        tv.setText(R.string.list);
        showItemList();
    }
}