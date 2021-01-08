export default class Auth{
	constructor(user){
		this.user = user
	}
	roles(){
		return this.user.roles.map(role=>role.name)
	}
	permissions(){
		return this.user.permissions.map(p=>p.name)
	}
	isAdmin(){
		return this.roles().includes('Admin')
	}
	can($permissionName){
		return true
		return this.permissions().includes($permissionName)
	}
}