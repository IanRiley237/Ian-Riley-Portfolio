using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;

public class GameController : MonoBehaviour
{
	public NodePath[] Path;
	private GameObject nodePath, thisEnemy, player;
	AudioSource sound;
	public AudioClip levelStart, levelLoop, bossStart, bossLoop;
	public bool boss = false;
	private int enemyIndex;
	public Text scoreText;
	public Animator loadScreen;

	public Image dialogueBox;
	public Text dialogueText;

	private float maxHealth;
	private Image healthBar;
	private Text percentage;
	private bool cutsceneInProgress = true, waited = false;
	private byte branchDialogue = 0;


	void Start ()
	{

		loadScreen.SetBool ("isEnd", false);
		dialogueBox.enabled = false;
		dialogueText.enabled = false;

		sound = GetComponent<AudioSource> ();
		Screen.SetResolution (600, 900, true);
		StartCoroutine(Spawn (0));
		Time.timeScale = 1;

		player = GameObject.FindGameObjectWithTag ("Player");



		Debug.Log (SystemInfo.graphicsDeviceName);

	}


	// Update is called once per frame
	void Update ()
	{
		scoreText.text = "Score: " + Data.score;
		Cursor.visible = false;
		ManageMusic ();




	}

	void ManageMusic()
	{
		sound.volume = Data.musicVolume;
		if (!boss)
		{
			if (sound.clip == null)
			{
				sound.clip = levelStart;
				sound.loop = false;
				sound.Play ();
			}
			if (!sound.isPlaying && sound.clip == levelStart)
			{
				sound.clip = levelLoop;
				sound.loop = true;
				sound.Play ();
			}
		} else
		{
			if (sound.clip != bossStart && sound.clip != bossLoop)
			{
				sound.clip = bossStart;
				sound.loop = false;
				sound.Play ();
			}
			if (!sound.isPlaying && sound.clip == bossStart)
			{
				sound.clip = bossLoop;
				sound.loop = true;
				sound.Play ();
			}
		}

	}

	IEnumerator checkForCutscene(int dialogueIndex, int spawnIndex)
	{
		cutsceneInProgress = true;
		if (SceneManager.GetActiveScene ().name == "Tutorial Level")
		{
			if (dialogueIndex == 33 && spawnIndex == 0)
			{
				dialogueText.enabled = false;
				dialogueBox.enabled = false;
				GameObject.FindGameObjectWithTag ("Player").GetComponent<PlayerControl> ().playerControllable = true;

				yield return new WaitForSeconds (5f);
			}
		}
		else if (SceneManager.GetActiveScene ().name == "Level 1")
		{
			if (dialogueIndex == 14 && spawnIndex == 0)
			{
				// Move the citizen bot into the scene
				GameObject bot = Path[spawnIndex].enemies[0];

				bot.GetComponent<Waypoints>().waypointList = new Transform[] {};

				Instantiate(bot, new Vector3(transform.position.x, transform.position.y-1.1f, transform.position.z), Quaternion.identity, transform);
			}

			if (dialogueIndex == 15 && spawnIndex == 0)
			{
				dialogueText.enabled = false;
				dialogueBox.enabled = false;
				GameObject.FindGameObjectWithTag ("Player").GetComponent<PlayerControl> ().playerControllable = true;

				yield return new WaitUntil (() => GameObject.FindGameObjectWithTag("Bolt"));

				GameObject.FindGameObjectWithTag ("Player").GetComponent<PlayerControl> ().playerControllable = false;

				yield return new WaitForSeconds (2f);

				if (GameObject.FindGameObjectWithTag ("Enemy") != null)
				{
					branchDialogue = 0;
				} else
				{
					branchDialogue = 1;
				}
			}
		}
		cutsceneInProgress = false;
	}


	IEnumerator Spawn(int index)
	{
		if (loadScreen.GetBool("isEnd"))
			yield break;
		
		GameObject nodes = Path[index].nodes;
		GameObject[] enemy = Path[index].enemies;
		float speed = Path[index].speed, rotation = Path[index].defaultRotation / 90, delayBegin = Path[index].delayBegin;
		int amount = Path[index].amount;

		yield return new WaitForSeconds (delayBegin);

		// Start Dialogue
		if (Path [index].waitUntilEnemiesAreGone)
		{
			yield return new WaitWhile (() => GameObject.FindWithTag ("Enemy") != null);
			//Time.timeScale = 0;

		}

		for (int i = 0; i < Path [index].whoTalking.Length; i++)
		{
			StartCoroutine(checkForCutscene (i, index));
			yield return new WaitUntil(() => (!cutsceneInProgress));

			GameObject.FindGameObjectWithTag ("Player").GetComponent<PlayerControl> ().playerControllable = false;

			dialogueText.enabled = true;
			dialogueBox.enabled = true;

			if (SceneManager.GetActiveScene ().name == "Level 1")
			{
				if (branchDialogue == 0 && i == 15)
				{
					i = 16;
				} else if (branchDialogue == 1 && i == 15)
				{
					i = 24;
				} else if (i == 23 || i == 31)
				{
					i = 32;
				}
			}

			dialogueText.text = Path [index].dialogue [i];
			dialogueBox.sprite = dialogueBox.GetComponent<DialogBoxBehavior> ().images [Path [index].whoTalking [i]];

			Debug.Log ("Begin wait");
			yield return new WaitUntil(() => (!Input.GetButton("Submit") && !Input.GetButton("Fire1")) == true);
			yield return new WaitUntil(() => ((Input.GetButtonDown("Submit") || Input.GetButtonDown("Fire1")) && Time.timeScale != 0) == true);
			Debug.Log ("End wait");
		}
		if (player != null)
			player.GetComponent<PlayerControl> ().playerControllable = true;
		else
			yield break;

		dialogueText.enabled = false;
		dialogueBox.enabled = false;

		// End Dialogue
 // Delay this path by the specified amount of time

		Debug.Log ("Wave" + (index + 1) + " Start.");

		if (index < Path.Length - 1)
			StartCoroutine(Spawn (index + 1)); // Begin the next path

		// For loop to spawn each enemy
		for (int i = 0; i < amount; i++)
		{
			enemyIndex = (int)(Random.value * enemy.Length);
			if (Path [index].randomXpos)
				nodePath = Instantiate (nodes, new Vector3 (nodes.transform.position.x + (Random.value * 52) - 27, nodes.transform.position.y, nodes.transform.position.z),
					new Quaternion (Quaternion.identity.x, Quaternion.identity.y, Quaternion.identity.z, Quaternion.identity.w), transform);
			else if (Path [index].randomZpos)
				nodePath = Instantiate (nodes, new Vector3 (nodes.transform.position.x, nodes.transform.position.y, nodes.transform.position.z + (Random.value * 40)),
					new Quaternion (Quaternion.identity.x, Quaternion.identity.y, Quaternion.identity.z, Quaternion.identity.w), transform);
			else
				nodePath = nodes;
		
			Vector3 spawnValues = new Vector3 (
				                     nodePath.transform.GetChild (0).transform.position.x,
				                     nodePath.transform.GetChild (0).transform.position.y,
				                     nodePath.transform.GetChild (0).transform.position.z);

			if (enemy [enemyIndex].gameObject.GetComponent<Behavior> () != null)
			{
				Behavior enemyBehavior = enemy [enemyIndex].gameObject.GetComponent<Behavior> ();
				enemyBehavior.rotateSpeed = Path [index].rotationSpeed;
				enemyBehavior.pingpong = Path [index].pingpong;
				enemyBehavior.rotateStart = Path [index].rotateStart;
				enemyBehavior.rotateEnd = Path [index].rotateEnd;
			}


			if (enemy [enemyIndex].transform.Find ("Gun") != null)
			{
				AimShoot gun = enemy [enemyIndex].transform.Find ("Gun").GetComponent<AimShoot> ();
				gun.aimable = Path [index].aimable;
			}

			Waypoints enemyWaypointClass = enemy [enemyIndex].gameObject.GetComponent<Waypoints> ();
			enemyWaypointClass.speed = speed;
			enemyWaypointClass.waypointList = new Transform[nodePath.transform.childCount];

			int j = 0;
			foreach (Transform point in nodePath.transform)
				enemyWaypointClass.waypointList [j++] = point;


			Instantiate (enemy [enemyIndex], spawnValues,
				new Quaternion (Quaternion.identity.x, Quaternion.identity.y + rotation, Quaternion.identity.z, Quaternion.identity.w), transform);
		

			// Debug.Log ("Gunbot Spawned.");
			yield return new WaitForSeconds (Path [index].delaySpawn);
		}
	}
}

[System.Serializable] public class NodePath
{
	public GameObject[] enemies; // The enemy that will follow the path
	public GameObject nodes; // The path of nodes that the enemy will follow. This will be an empty GameObject with multiple children.
	public float speed, /* How fast the enemies will move */ rotationSpeed = 0, defaultRotation = 0, rotateStart = 0, rotateEnd = 360,
		delaySpawn /* The amount of time between each enemy spawning */ , delayBegin /* The amount of time before the path begins after the previous path */;
	public int amount;
	public bool pingpong = false, randomXpos = false, randomZpos = false, aimable = false;

	public bool dialogueBeforeWave;
	public string[] dialogue;
	public byte [] whoTalking;
	public bool waitUntilEnemiesAreGone = false;
}
