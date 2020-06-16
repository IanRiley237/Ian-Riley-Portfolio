using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class TerrainMove : MonoBehaviour
{
    public int stoppingPoint;
	public float speed = 1;
	public bool cam;

	// Use this for initialization
	void Start ()
    {
		
	}
	
	// Update is called once per frame
	void Update ()
    {
		if (!cam)
		{
			if (this.transform.position.z > stoppingPoint)
				transform.Translate (0, 0, -speed * Time.deltaTime);
		} else
		{
			if (this.transform.position.z < stoppingPoint)
				transform.Translate (0, -speed * Time.deltaTime, 0);
		}
	}
}
