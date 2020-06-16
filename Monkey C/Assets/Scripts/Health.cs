using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;

public class Health : MonoBehaviour {

	public int health, maxHealth;
	public Image healthBarContainer;
	public GameObject explosion;

	Image healthBar;
	Image bossHealthBarContainer;
	Image bossHealthBar;
	Text percentage;

	bool dead = false;

	// Use this for initialization
	void Start () {

		bossHealthBarContainer = GameObject.Find("BossHealthBarContainer").GetComponent<Image>();
		bossHealthBar = GameObject.Find("BossHealthBar").GetComponent<Image>();
		percentage = GameObject.Find("Percentage") .GetComponent<Text>();

		if (tag == "Player")
		{
			healthBarContainer.enabled = true;
			healthBar = GameObject.Find("HealthBar").GetComponent<Image>();
			healthBar.enabled = true;
		}
		if (tag != "Asteroid")
		{
			bossHealthBar.enabled = false;
			bossHealthBarContainer.enabled = false;
			maxHealth = health;
		}

	}
	
	// Update is called once per frame
	void Update () {
		float percentageValue = (float)health / (float)maxHealth;
		float difference;
		if (healthBarContainer != null)
		{

			if (tag == "Player")
			{
				difference = healthBar.fillAmount - percentageValue;
				if (healthBar.fillAmount > percentageValue)
					healthBar.fillAmount -= Time.deltaTime * (difference < 0.18f ? 0.18f : difference);
				if (healthBar.fillAmount < percentageValue)
					healthBar.fillAmount = percentageValue;
				percentage.text = (healthBar.fillAmount * 100f).ToString("0.#\\%");
			}

			if (tag == "Ivan" || tag == "Spangle" || tag == "StarBro")
			{
				bossHealthBar.enabled = true;
				bossHealthBarContainer.enabled = true;
				difference = bossHealthBar.fillAmount - percentageValue;
				if (bossHealthBar.fillAmount > percentageValue)
					bossHealthBar.fillAmount -= Time.deltaTime * (difference < 0.18f ? 0.18f : difference);
				if (bossHealthBar.fillAmount < percentageValue)
					bossHealthBar.fillAmount = percentageValue;
			}

		}

		if (health <= 0 && !dead)
		{
			dead = true;
			if (tag == "Ivan" || tag == "Spangle" || tag == "StarBro")
			{
				bossHealthBar.fillAmount = 0;
			}
			
			if (GetComponent<DestroyByContact> () != null)
			{
				Data.score += GetComponent<DestroyByContact> ().scoreValue;
			}

			if (tag == "Ivan")
				GameObject.Find("Action Camera").GetComponent<SceneLoader>().Starter (1.5f, "Level 1");
			else if (tag == "StarBro")
				GameObject.Find("Action Camera").GetComponent<SceneLoader>().Starter (1.5f, "Level 2");
			else if (tag == "Spangle")
				GameObject.Find("Action Camera").GetComponent<SceneLoader>().Starter (1.5f, "Main Menu");
			else if (tag == "Asteroid")
			{
				/*	GameObject asteroidPiece = Instantiate (
					gameObject,
					new Vector3 (gameObject.transform.localPosition.x + 3, gameObject.transform.localPosition.y, gameObject.transform.localPosition.z),
					gameObject.transform.rotation,
					GameObject.Find ("Action").transform);
				asteroidPiece.transform.localScale = new Vector3 (100f, 100f, 100f);
				asteroidPiece.tag = "Piece";
				asteroidPiece.GetComponent<Health> ().health = 1;
				asteroidPiece.GetComponent<Waypoints> ().enabled = false;
				asteroidPiece.GetComponent<Rigidbody>().velocity = new Vector3 (10f, 0f, 0f);


				asteroidPiece = Instantiate (
					gameObject,
					new Vector3 (gameObject.transform.localPosition.x - 3, gameObject.transform.localPosition.y, gameObject.transform.localPosition.z),
					gameObject.transform.rotation,
					GameObject.Find ("Action").transform);
				asteroidPiece.transform.localScale = new Vector3 (100f, 100f, 100f);
				asteroidPiece.tag = "Piece";
				asteroidPiece.GetComponent<Health> ().health = 1;
				asteroidPiece.GetComponent<Waypoints> ().enabled = false;
				asteroidPiece.GetComponent<Rigidbody>().velocity = new Vector3 (10, 0, 0);*/
			} else if (tag == "Player")
			{
				//GameObject.Find("Action Camera").GetComponent<SceneLoader>().Starter (3f);
			}


			if (tag == "Ivan" || tag == "Spangle")
			{
				AimShoot[] guns = GetComponentsInChildren<AimShoot> ();

				GetComponent<Waypoints> ().speed = 0;

				foreach (AimShoot gun in guns)
					gun.broken = true;
				if (tag == "Spangle")
					StartCoroutine (WaitToLeave ());
			} else
			{
				Instantiate (explosion, transform.position, transform.rotation);
				Destroy (this.gameObject);
			}
		}
	}

	IEnumerator WaitToLeave()
	{
		yield return new WaitUntil (() => Input.GetButtonDown ("Submit") || Input.GetButtonDown ("Fire1"));
		GetComponent<Rigidbody> ().velocity = new Vector3 (0, 0, 5);

	}
}
