using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Behavior : MonoBehaviour {
	public bool pingpong = false;
	public float rotateSpeed = 0, rotateStart = 0, rotateEnd = 360;

	private bool positive;
	// Use this for initialization
	void Start ()
	{
		positive = (rotateSpeed >= 0) ? true : false;
		if (tag == "Ivan" || tag == "Spangle" || tag == "StarBro")
			GameObject.Find ("Action").GetComponent<GameController> ().boss = true;
	}

	// Update is called once per frame
	void Update ()
	{
		//transform.Rotate (Vector3.up * (rotateSpeed * Time.deltaTime));

		if (rotateSpeed != 0)
			transform.Rotate(Vector3.up * rotateSpeed, Space.World);
		if (pingpong && ((transform.rotation.y < rotateEnd && positive) || (transform.rotation.y < rotateStart && !positive)))
		{
			rotateSpeed = -rotateSpeed;
			positive = !positive;
		}
	}
}
