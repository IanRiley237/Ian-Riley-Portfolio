using UnityEngine;
using System.Collections;

public class RandomRotator : MonoBehaviour 
{
	public float tumble;
	public bool random;
	void Start ()
	{
		if (random)
			tumble = Random.value * tumble + 1;
		GetComponent<Rigidbody>().angularVelocity = Random.insideUnitSphere * tumble;
	}
}