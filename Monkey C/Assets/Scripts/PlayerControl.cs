using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;
using System.Collections;

[System.Serializable]

public class PlayerControl : MonoBehaviour
{
	public float speed;
	public float tilt;
	public GameObject boundary;
	public GameObject shot;
	public GameObject screenClearEffect;
	public Transform shotSpawn;
	public float fireRate;
	public int bombs = 3;
	public Image[] bombImages;
	public Text count;
	public GameObject pauseMenu;
	public Image[] button;
	public GameObject sliders;
	public bool playerControllable = true;

	private bool[] selected;
	private int index = 0;
	private float nextFire;
	private bool invincible = false, screenClear = false;

	void Start()
	{
		pauseMenu.SetActive (false);
		sliders.SetActive (false);
		selected = new bool[button.Length];


		button [3].transform.parent.parent.GetComponent<Slider> ().value = Data.musicVolume;
		button [4].transform.parent.parent.GetComponent<Slider> ().value = Data.effectVolume;
	}

	void Update ()
	{

		GetComponent<AudioSource> ().volume = Data.effectVolume;

		if (Input.GetButtonDown("Cancel"))
		{
			if (sliders.activeSelf)
			{
				Select (2);
				sliders.SetActive (false);
			}
			else if (Time.timeScale == 0)
			{
				Time.timeScale = 1;
				sliders.SetActive (false);
				pauseMenu.SetActive (false);
			}
			else
			{
				Time.timeScale = 0;
				pauseMenu.SetActive (true);
			}
		}

		// Pause menu
		if (pauseMenu.activeSelf)
		{
			//playerControllable = false;
			if (Input.GetKeyDown (KeyCode.DownArrow) || Input.GetKeyDown(KeyCode.S))
			{
				if (index == 3)
					Select (4);
				else if (index == 4)
					Select (3);
				else if (index != 2)
					Select (index + 1);
				else
					Select (0);
			} else if (Input.GetKeyDown (KeyCode.UpArrow) || Input.GetKeyDown(KeyCode.W))
			{
				if (index == 3)
					Select (4);
				else if (index == 4)
					Select (3);
				else 
					if (index != 0)
						Select (index - 1);
				else
					Select (2);
			}
			else if (Input.GetButtonDown ("Submit"))
			{
				switch (index)
				{
				case 0:
					SceneManager.LoadScene ("Main Menu");
					break;
				case 1:
					GameObject.Find("Action Camera").GetComponent<SceneLoader>().Starter (0f);
					break;
				case 2:
					sliders.SetActive (!sliders.activeSelf);
					Select (3);
					//playerControllable = !playerControllable;
					break;
				default:
					break;
				}
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
			return;
		}
		// Pause menu end	


		
	}

	public bool isInvincible()
	{
		return invincible;
	}
	public void setInvincible(bool i)
	{
		invincible = i;
	}

	void FixedUpdate ()
	{
		if (!playerControllable)
		{
			GetComponent<SphereCollider> ().enabled = false;
			GetComponent<Rigidbody>().velocity = new Vector3(0 , 0 , 0);
			return;
		}
		if (GetComponent<SphereCollider> ().enabled == false)
			StartCoroutine (TimeClear (1.5f));
		if (Input.GetButton ("Fire1") && Time.time > nextFire)
		{
			GetComponent<AudioSource> ().Play ();
			nextFire = Time.time + fireRate;
			GameObject spawnedShot = Instantiate (shot, shotSpawn.position, shotSpawn.rotation);
			spawnedShot.transform.parent = GameObject.Find ("Action").transform;
			//	Debug.Log ("Shot Fired.");
		}

		if (Input.GetButtonDown ("Fire2") && bombs-- > 0)
		{
			StartCoroutine (TimeClear (2.0f));
			Instantiate (screenClearEffect, transform.position, screenClearEffect.transform.rotation, transform);
		}

		if (screenClear)
			foreach (var bolt in GameObject.FindGameObjectsWithTag("EnemyBolt"))
				Destroy (bolt.gameObject);

		if (bombs > bombImages.Length)
		{
			for (int i = 0; i < bombImages.Length; i++)
				bombImages [i].enabled = false;
			bombImages [1].enabled = true;
			count.enabled = true;
		} else
		{
			for (int i = 0; i < bombImages.Length; i++)
				if (i < bombs)
					bombImages [i].enabled = true;
				else
					bombImages [i].enabled = false;
			count.enabled = false;
		}
		count.text = "x " + bombs;

		Rigidbody bound = boundary.GetComponent<Rigidbody>();
		float moveHorizontal = Input.GetAxis ("Horizontal");
		float moveVertical = Input.GetAxis ("Vertical");

		Vector3 movement = new Vector3 (moveHorizontal, 0.0f, moveVertical);
		GetComponent<Rigidbody>().velocity = movement * speed;

		GetComponent<Rigidbody>().position = new Vector3
			(
				Mathf.Clamp (GetComponent<Rigidbody>().position.x, bound.position.x -26, bound.position.x + 26), 
				GetComponent<Rigidbody>().position.y, 
				Mathf.Clamp (GetComponent<Rigidbody>().position.z, bound.position.z -34, bound.position.z + 40)
			);

		// Rotate the player
		GetComponent<Rigidbody>().rotation = Quaternion.Euler (0.0f, 180.0f, GetComponent<Rigidbody>().velocity.x * tilt);
	}

	IEnumerator TimeClear(float time)
	{
		screenClear = true;
		GetComponent<SphereCollider> ().enabled = false;
		yield return new WaitForSeconds (time);
		screenClear = false;
		GetComponent<SphereCollider> ().enabled = true;
	}

	public void CallClear(float time)
	{
		StartCoroutine(TimeClear(time));
	}
	/*
	public IEnumerator Flasher(int flashes)
	{
		setInvincible (true);
		MeshRenderer[] mesh = GameObject.FindGameObjectWithTag ("Player").GetComponentsInChildren<MeshRenderer> ();

		for (int j = 0; j < mesh.Length; j++)
			mesh [j].enabled = !mesh [j].enabled;
		yield return new WaitForSeconds (0.25f);
		Flasher (--flashes);
		setInvincible (false);
	}
	*/
	void Select(int selection)
	{
		selection = selection % button.Length;
		if (selection < 0 || (!sliders.activeSelf && button[selection].GetComponentInChildren<Text>() == null))
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
