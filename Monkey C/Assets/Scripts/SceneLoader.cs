using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;

public class SceneLoader : MonoBehaviour {

	public GameObject explosion;
	int scoreAtStart;
	public float bossTime = 0.0f;

	public Image dialogueBox;
	public Text dialogueText;

	public string[] endDialogue;

	public byte [] whoTalking;

	public Animator loadingScreen;
	public Text endText, prompt;
	private Text percentage;


	private float maxHealth;
	private Image healthBar;
	private GameObject player;
	private bool waited;


	private GameObject pauseMenu;
	private Image[] button;
	private GameObject sliders;
	private bool[] selected;
	private int index = 0;

	// Use this for initialization
	void Start () {
		scoreAtStart = Data.score;
		player = GameObject.FindGameObjectWithTag ("Player");
		maxHealth = player.GetComponent<Health> ().maxHealth;
		healthBar = GameObject.Find("HealthBar").GetComponent<Image>();
		percentage = GameObject.Find("Percentage") .GetComponent<Text>();


		pauseMenu = player.GetComponent<PlayerControl> ().pauseMenu;
		button = player.GetComponent<PlayerControl> ().button;
		sliders = player.GetComponent<PlayerControl> ().sliders;


		selected = new bool[button.Length];
		button [3].transform.parent.parent.GetComponent<Slider> ().value = Data.musicVolume;
		button [4].transform.parent.parent.GetComponent<Slider> ().value = Data.effectVolume;
	}
	
	// Update is called once per frame
	void Update () {
		Debug.Log (Time.time + " and " + (bossTime + 70.0f) + " and " + (!dialogueBox.enabled) + " and " + (explosion != null));
		if (Time.time > bossTime + 70.0f && bossTime != 0 && !dialogueBox.enabled && explosion != null)
		{
			explosion.transform.localScale = new Vector3(5f, 5f, 5f);
			Instantiate(explosion);
			explosion.transform.localScale = new Vector3(1f, 1f, 1f);
			explosion = null;
		}

		if (player == null)
		{
			loadingScreen.SetBool ("isEnd", true);
			pauseMenu.SetActive (true);

			if (waited)
				Pause ();
			else
				StartCoroutine( Wait (1.5f));

			float percentageValue = 0f / (float)maxHealth;
			float difference;

			difference = healthBar.fillAmount - percentageValue;
			if (healthBar.fillAmount > percentageValue)
				healthBar.fillAmount -= Time.deltaTime * (difference < 0.18f ? 0.18f : difference);
			if (healthBar.fillAmount < percentageValue)
				healthBar.fillAmount = percentageValue;
			percentage.text = (healthBar.fillAmount * 100f).ToString("0.#\\%");
		}
	}

	IEnumerator Wait(float t)
	{
		yield return new WaitForSeconds (t);
		waited = true;
	}

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
	void Pause()
	{
		if (Input.GetKeyDown (KeyCode.DownArrow) || Input.GetKeyDown(KeyCode.S))
		{
			if (sliders.activeSelf || index != 2)
				Select (index + 1);
			else
				Select (0);
		} else if (Input.GetKeyDown (KeyCode.UpArrow) || Input.GetKeyDown(KeyCode.W))
		{
			if (sliders.activeSelf || index != 0)
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
	public void Starter(float t /*seconds to wait*/, string scene /*level to start*/)
	{
		StartCoroutine(StartNextScene(t, scene));
	}

	public void Starter(float t /*seconds to wait*/)
	{
		StartCoroutine(RestartScene(t));
	}

	IEnumerator RestartScene(float t /*seconds to wait*/)
	{
		yield return new WaitForSeconds (t);
		Data.score = scoreAtStart;
		SceneManager.LoadScene (SceneManager.GetActiveScene ().name);
	}

	IEnumerator StartNextScene(float t /*seconds to wait*/, string scene /*level to start*/)
	{

		dialogueBox.enabled = true;
		dialogueText.enabled = true;

		for (int i = 0; i < endDialogue.Length; i++)
		{
			GameObject.FindGameObjectWithTag ("Player").GetComponent<PlayerControl> ().playerControllable = false;

			dialogueText.enabled = true;
			dialogueBox.enabled = true;

			if (SceneManager.GetActiveScene ().name == "Level 2")
			{
				if (Time.time < bossTime + 70.0f && i == 0)
				{
					i = 1;
				} else if (Time.time > bossTime + 70.0f  && i == 0)
				{
					i = 10;
				} else if (i == 9 || i == 17)
				{
					i = 18;
				}
			}
		
			dialogueText.text = endDialogue[i];
			dialogueBox.sprite = dialogueBox.GetComponent<DialogBoxBehavior> ().images [whoTalking[i]];

			Debug.Log ("Begin wait");
			yield return new WaitUntil(() => (!Input.GetButton("Submit") && !Input.GetButton("Fire1")) == true);
			yield return new WaitUntil(() => ((Input.GetButtonDown("Submit") || Input.GetButtonDown("Fire1")) && Time.timeScale != 0) == true);
			Debug.Log ("End wait");
		}

		dialogueText.enabled = false;
		dialogueBox.enabled = false;

		prompt.enabled = true;
		endText.text = "Mission Complete";
		loadingScreen.SetBool ("isEnd", true);

		yield return new WaitForSeconds (1.5f);
		yield return new WaitUntil(() => Input.GetButtonDown("Submit"));

		SceneManager.LoadScene (scene, LoadSceneMode.Single);
	}
}
