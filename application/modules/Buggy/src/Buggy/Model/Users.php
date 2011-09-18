<?php
namespace Buggy\Model;

/**
 * @Entity(repositoryClass="Buggy\Model\UsersRepository")
 * @Table(name="users")
 */
class Users 
{
	/** 
	 * @Id 
	 * @GeneratedValue
	 * @Column(type="smallint")
	 */
    private $id;
    
    /** 
     * @Column(length=255) 
     */
    private $username;
    
    /** 
     * @Column(length=255)  
     */
    private $password;
    
    /** 
     * @Column(length=255)  
     */
    private $salt;
    
    /** 
     * @Column(length=255)  
     */
    private $name;
    
    /** 
     * @Column(length=255)  
     */
    private $mail;
    
    /** 
     * @Column(length=255)  
     */
    private $role;
    
	
}