using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;

public class MenuController : MonoBehaviour {

	public Image[] button;
	public Image background;
	public Animator animator;

	bool[] selected;
	int index = 0;
	// Use this for initialization
	void Start () {
		background.GetComponent<Animation> ().Play ();
		selected = new bool[button.Length];
		Time.timeScale = 1;
		Data.score = 0;

		button [3].transform.parent.parent.GetComponent<Slider> ().value = Data.musicVolume;
		button [4].transform.parent.parent.GetComponent<Slider> ().value = Data.effectVolume;

	}
	
	// Update is called once per frame
	void Update () {
		Cursor.visible = false;
		GetComponent<AudioSource> ().volume = Data.musicVolume * 0.5f;

		if (Input.GetKeyDown (KeyCode.DownArrow) || Input.GetKeyDown (KeyCode.S))
		{
			if (index == 3)
				Select (4);
			else if (index == 4)
				Select (3);
			else if (index != 2)
				Select (index + 1);
			else
				Select (0);
		} else if (Input.GetKeyDown (KeyCode.UpArrow) || Input.GetKeyDown (KeyCode.W))
		{
			if (index == 3)
				Select (4);
			else if (index == 4)
				Select (3);
			else if (index != 0)
				Select (index - 1);
			else
				Select (2);
		} else if (Input.GetButtonDown ("Submit"))
		{
			switch (index)
			{
			case 0:
				SceneManager.LoadScene ("Tutorial Level");
				break;
			case 1:
				animator.SetBool ("isHidden", !animator.GetBool ("isHidden"));
				Select (3);
				break;
			case 2:
				Application.Quit ();
				break;
			default:
				break;
			}
		} else if (Input.GetButtonDown ("Cancel") && !animator.GetBool ("isHidden"))
		{
			Select (1);
			animator.SetBool ("isHidden", true);
		}
		if (index == 3 || index == 4)
		{
			if (Input.GetKeyDown (KeyCode.RightArrow) || Input.GetKeyDown(KeyCode.D))
			{
				if (Input.GetKey(KeyCode.LeftShift))
					button [index].transform.parent.parent.GetComponent<Slider> ().value += 0.01f;
				else
					button [index].transform.parent.parent.GetComponent<Slider> ().value += 0.1f;
				if (index == 4)
				{
					button [4].GetComponent<AudioSource> ().volume = Data.effectVolume;
					button [4].GetComponent<AudioSource> ().Play ();
				}
			}
			if (Input.GetKeyDown (KeyCode.LeftArrow) || Input.GetKeyDown(KeyCode.A))
			{
				if (Input.GetKey(KeyCode.LeftShift))
					button [index].transform.parent.parent.GetComponent<Slider> ().value -= 0.01f;
				else
					button [index].transform.parent.parent.GetComponent<Slider> ().value -= 0.1f;
				if (index == 4)
				{
					button [4].GetComponent<AudioSource> ().volume = Data.effectVolume;
					button [4].GetComponent<AudioSource> ().Play ();
				}
			}
			Data.musicVolume = button [3].transform.parent.parent.GetComponent<Slider> ().value;
			Data.effectVolume = button [4].transform.parent.parent.GetComponent<Slider> ().value;
		}
	}

	void Select(int selection)
	{
		selection = selection % button.Length;
		if (selection < 0)
			selection = button.Length - 1;
		for (int i = 0; i < button.Length; i++)
		{
			selected [i] = false;
			if (button [i].GetComponentInChildren<Text> () != null)
			{
				button [i].GetComponentInChildren<Text> ().fontStyle = FontStyle.Normal;
				button [i].GetComponentInChildren<Text> ().color = Color.white;
			} else
			{
				button [i].GetComponent<RectTransform> ().localScale = new Vector3 (1f, 1f, 1f);
				button [i].GetComponent<Image> ().color = new Color (214f / 255f, 111f / 255f, 0);
			}
		}

		selected [selection] = true;

		if (button [selection].GetComponentInChildren<Text> () != null)
		{
			button [selection].GetComponentInChildren<Text> ().fontStyle = FontStyle.Bold;
			button [selection].GetComponentInChildren<Text> ().color = Color.yellow;
		} else
		{
			button [selection].GetComponent<RectTransform> ().localScale = new Vector3 (1.3f, 1.3f, 1.3f);
			button [selection].GetComponent<Image> ().color = new Color (214f / 255f, 60f / 255f, 0);
		}

		index = selection;
	}
}
