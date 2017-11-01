const helpers = require("./helpers.js");

class Group {
    constructor(memberLimit) {
        this.memberLimit = memberLimit;
    }

    addMembers(prospectiveMembers) {
        const members = [];
        while (members.length < this.memberLimit && prospectiveMembers.length > 0) {
            const member = prospectiveMembers.shift();
            members.push(member);
        }

        return {
            remaining: prospectiveMembers,
            members: members
        }
    }
}

class Groups {
    constructor(prospectiveMembers, memberLimit) {
        const groups = [];

        while (prospectiveMembers.length > 0) {
            const group = new Group(memberLimit);
            const result = group.addMembers(prospectiveMembers);

            prospectiveMembers = result.remaining;
            groups.push(result.members);
        }

        return groups;
    }
}

module.exports = Groups;
