# SHOWROOM API

Unofficial SHOWROOM API

### Endpoint Usage

----

### Authentication Endpoint

| HTTP Verbs | Endpoints | Action |
| --- | --- | --- |
| POST | /api/login | To login an existing user account |
| POST | /api/register | To sign up a new user account |


### Live Endpoint

| HTTP Verbs | Endpoints | Action |
| --- | --- | --- |
| POST | /api/live/comment | To comment in the livestream |
| POST | /api/live/send_gift | To gift stars in the livestream |
| POST | /api/live/bulk_gift | To gift all stars in the livestream |

### Room Endpoint

| HTTP Verbs | Endpoints | Action |
| --- | --- | --- |
| POST | /api/room/follow | To follow the room |
| POST | /api/room/followed_rooms | To retrieve all the followed rooms |

### Profile Endpoint

| HTTP Verbs | Endpoints | Action |
| --- | --- | --- |
| POST | /api/profile/user | To retrieve the current user profile |
| POST | /api/profile/detail | To retrieve the current user profile detail |
| POST | /api/profile/update_profile | To edit the current user profile |
| POST | /api/profile/update_avatar | To edit the current user avatar |
| POST | /api/profile/get_avatar | To retrieve all available avatars |


### Showcase
----

* JKT48 Showroom  

https://jkt48-showroom.vercel.app/
